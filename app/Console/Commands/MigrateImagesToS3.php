<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class MigrateImagesToS3 extends Command
{
    protected $signature = 'storage:migrate-to-s3 {--dry-run : Preview without uploading} {--limit=100 : Number of images to process}';
    protected $description = 'Migrate images from production server to S3 storage';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $limit = (int) $this->option('limit');
        
        $this->info('🚀 Starting image migration to S3...');
        
        if ($isDryRun) {
            $this->warn('📋 DRY RUN MODE - No files will be uploaded');
        }

        $images = DB::table('images')
            ->whereNotNull('path')
            ->limit($limit)
            ->get();

        $this->info("📦 Found {$images->count()} images to process");
        
        $progressBar = $this->output->createProgressBar($images->count());
        $progressBar->start();

        $stats = ['success' => 0, 'failed' => 0, 'skipped' => 0];
        
        foreach ($images as $image) {
            $progressBar->advance();
            
            if (empty($image->path)) {
                $stats['skipped']++;
                continue;
            }

            $productionUrl = env('PRODUCTION_STORAGE_URL', 'https://vsearmyane.ru/storage') . '/' . $image->path;

            if (!$isDryRun) {
                try {
                    $response = Http::timeout(30)->get($productionUrl);
                    
                    if ($response->successful()) {
                        Storage::disk('s3')->put($image->path, $response->body(), 'public');
                        $stats['success']++;
                    } else {
                        $stats['failed']++;
                        $this->error("\n❌ Failed to download: {$image->path} (HTTP {$response->status()})");
                    }
                } catch (\Exception $e) {
                    $stats['failed']++;
                    $this->error("\n❌ Error: {$image->path}: " . $e->getMessage());
                }
            } else {
                // Dry run - just show what would be processed
                $stats['success']++;
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info('✅ Migration completed!');
        $this->table(
            ['Status', 'Count'],
            [
                ['✅ Success', $stats['success']],
                ['❌ Failed', $stats['failed']],
                ['⏭️  Skipped', $stats['skipped']],
            ]
        );

        if ($isDryRun) {
            $this->warn('🔄 Run without --dry-run to actually upload files');
        }

        return Command::SUCCESS;
    }
}
