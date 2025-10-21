<?php

namespace App\Console\Commands;

use App\Models\Image;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MigrateImagesToS3 extends Command
{
    protected $signature = 'images:migrate-to-s3 {--limit=100 : Number of images to migrate} {--force : Skip confirmation}';
    
    protected $description = 'Migrate images from production server to S3 storage';

    public function handle()
    {
        $limit = (int) $this->option('limit');
        
        if (!$this->option('force')) {
            if (!$this->confirm("This will migrate up to {$limit} images from production to S3. Continue?")) {
                $this->info('Migration cancelled.');
                return 0;
            }
        }

        $images = Image::whereNotNull('path')
            ->where('path', '!=', '')
            ->limit($limit)
            ->get();

        $total = $images->count();
        $this->info("Found {$total} images to migrate");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $success = 0;
        $failed = 0;
        $skipped = 0;

        foreach ($images as $image) {
            try {
                if (Storage::disk('s3')->exists($image->path)) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                $productionUrl = env('PRODUCTION_STORAGE_URL', 'https://vsearmyne.ru/storage') . '/' . $image->path;
                
                $response = Http::timeout(30)->get($productionUrl);
                
                if ($response->successful()) {
                    Storage::disk('s3')->put(
                        $image->path,
                        $response->body(),
                        'public'
                    );
                    $success++;
                } else {
                    $this->newLine();
                    $this->warn("Failed to download: {$image->path} (HTTP {$response->status()})");
                    $failed++;
                }
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Error migrating {$image->path}: " . $e->getMessage());
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Migration completed!");
        $this->table(
            ['Status', 'Count'],
            [
                ['Success', $success],
                ['Skipped (already exists)', $skipped],
                ['Failed', $failed],
                ['Total', $total],
            ]
        );

        if ($success > 0) {
            $this->newLine();
            $this->info("To start using S3 images, set USE_S3_STORAGE=true in your .env file");
        }

        return 0;
    }
}
