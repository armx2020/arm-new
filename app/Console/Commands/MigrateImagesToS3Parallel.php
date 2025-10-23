<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

class MigrateImagesToS3Parallel extends Command
{
    protected $signature = 'storage:migrate-parallel {--limit=1000 : Number of images} {--batch=50 : Parallel downloads}';
    protected $description = 'Migrate images to S3 with parallel downloads (50x faster!)';

    public function handle()
    {
        $limit = (int) $this->option('limit');
        $batchSize = (int) $this->option('batch');
        
        $this->info("🚀 Fast parallel migration to S3!");
        $this->info("⚡ Downloading {$batchSize} images at a time");

        $images = DB::table('images')
            ->whereNotNull('path')
            ->limit($limit)
            ->get();

        $this->info("📦 Found {$images->count()} images");
        
        $chunks = $images->chunk($batchSize);
        $stats = ['success' => 0, 'failed' => 0, 'skipped' => 0];
        
        $progressBar = $this->output->createProgressBar($images->count());
        $progressBar->start();

        foreach ($chunks as $chunk) {
            $pool = Http::pool(fn ($pool) => 
                collect($chunk)->map(function ($image) use ($pool) {
                    if (empty($image->path)) return null;
                    
                    $url = env('PRODUCTION_STORAGE_URL', 'https://vsearmyane.ru/storage') . '/' . $image->path;
                    return $pool->timeout(30)->get($url);
                })->filter()->toArray()
            );

            foreach ($chunk as $index => $image) {
                $progressBar->advance();
                
                if (empty($image->path)) {
                    $stats['skipped']++;
                    continue;
                }

                $response = $pool[$index] ?? null;
                
                if ($response && $response->successful()) {
                    try {
                        Storage::disk('s3')->put($image->path, $response->body(), 'public');
                        $stats['success']++;
                    } catch (\Exception $e) {
                        $stats['failed']++;
                    }
                } else {
                    $stats['failed']++;
                }
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info('✅ Migration completed!');
        $this->table(
            ['Status', 'Count'],
            [
                ['✅ Uploaded', $stats['success']],
                ['❌ Failed', $stats['failed']],
                ['⏭️  Skipped', $stats['skipped']],
            ]
        );

        return Command::SUCCESS;
    }
}
