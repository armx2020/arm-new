<?php

namespace App\Console\Commands;

use App\Models\Image;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateLocalImagesToS3 extends Command
{
    protected $signature = 'images:migrate-local-to-s3 {--limit=100 : Number of images to migrate} {--force : Skip confirmation}';
    
    protected $description = 'Migrate images from local storage to S3 (run this on production server)';

    public function handle()
    {
        $limit = (int) $this->option('limit');
        
        if (!$this->option('force')) {
            if (!$this->confirm("This will migrate up to {$limit} images from local storage to S3. Continue?")) {
                $this->info('Migration cancelled.');
                return 0;
            }
        }

        $localStoragePath = storage_path('app/public');
        
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

                $localPath = $localStoragePath . '/' . $image->path;
                
                if (!file_exists($localPath)) {
                    $this->newLine();
                    $this->warn("Local file not found: {$localPath}");
                    $failed++;
                    $bar->advance();
                    continue;
                }

                $fileContents = file_get_contents($localPath);
                
                Storage::disk('s3')->put(
                    $image->path,
                    $fileContents,
                    'public'
                );
                
                $success++;
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
