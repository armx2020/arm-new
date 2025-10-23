<?php
/**
 * Bulk upload images archive to S3
 * Downloads tar.gz from production, extracts, uploads to S3
 */

require __DIR__.'/vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$startTime = microtime(true);

echo "🚀 Bulk S3 Migration Script\n";
echo str_repeat("=", 50) . "\n\n";

// S3 Configuration
$s3Config = [
    'version' => 'latest',
    'region'  => 'ru-1',
    'endpoint' => 'https://s3.twcstorage.ru',
    'use_path_style_endpoint' => false,
    'credentials' => [
        'key'    => getenv('S3_ACCESS_KEY') ?: getenv('AWS_ACCESS_KEY_ID'),
        'secret' => getenv('S3_SECRET_KEY') ?: getenv('AWS_SECRET_ACCESS_KEY'),
    ],
];

$bucketName = '46885a37-67c8e067-4002-4498-a06b-cb98be807ea3';
$archiveUrl = 'https://vsearmyane.ru/storage-images.tar.gz';
$localArchive = '/tmp/storage-images.tar.gz';
$extractPath = '/tmp/storage-extracted';

try {
    // Step 1: Download archive
    echo "📥 Step 1: Downloading archive from production...\n";
    echo "URL: $archiveUrl\n";
    
    $ch = curl_init($archiveUrl);
    $fp = fopen($localArchive, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 600); // 10 minutes
    curl_setopt($ch, CURLOPT_NOPROGRESS, false);
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function($resource, $downloadSize, $downloaded) {
        if ($downloadSize > 0) {
            $percent = round(($downloaded / $downloadSize) * 100, 1);
            echo "\rDownloading: {$percent}% (" . formatBytes($downloaded) . " / " . formatBytes($downloadSize) . ")";
        }
    });
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    fclose($fp);
    
    if (!$result || $httpCode !== 200) {
        throw new Exception("Failed to download archive. HTTP Code: $httpCode");
    }
    
    $archiveSize = filesize($localArchive);
    echo "\n✅ Downloaded: " . formatBytes($archiveSize) . "\n\n";
    
    // Step 2: Extract archive
    echo "📦 Step 2: Extracting archive...\n";
    
    if (!is_dir($extractPath)) {
        mkdir($extractPath, 0755, true);
    }
    
    $phar = new PharData($localArchive);
    $phar->extractTo($extractPath);
    
    echo "✅ Archive extracted\n\n";
    
    // Step 3: Find all images
    echo "🔍 Step 3: Finding all image files...\n";
    
    $uploadedPath = $extractPath . '/storage/uploaded';
    if (!is_dir($uploadedPath)) {
        throw new Exception("Uploaded directory not found: $uploadedPath");
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($uploadedPath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    $imageFiles = [];
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $ext = strtolower($file->getExtension());
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $imageFiles[] = $file->getPathname();
            }
        }
    }
    
    $totalFiles = count($imageFiles);
    echo "✅ Found $totalFiles image files\n\n";
    
    // Step 4: Upload to S3
    echo "☁️  Step 4: Uploading to S3...\n";
    echo "Bucket: $bucketName\n\n";
    
    $s3Client = new S3Client($s3Config);
    
    $uploaded = 0;
    $failed = 0;
    $failedFiles = [];
    
    foreach ($imageFiles as $index => $filePath) {
        // Get relative path from uploaded/ folder
        $relativePath = str_replace($uploadedPath . '/', '', $filePath);
        $s3Key = 'uploaded/' . $relativePath;
        
        $progress = $index + 1;
        $percent = round(($progress / $totalFiles) * 100, 1);
        
        echo "\r[$progress/$totalFiles] {$percent}% - Uploading: $relativePath";
        
        try {
            $result = $s3Client->putObject([
                'Bucket' => $bucketName,
                'Key'    => $s3Key,
                'SourceFile' => $filePath,
                'ACL'    => 'public-read',
                'ContentType' => mime_content_type($filePath),
            ]);
            
            $uploaded++;
            
        } catch (AwsException $e) {
            $failed++;
            $failedFiles[] = [
                'file' => $relativePath,
                'error' => $e->getMessage()
            ];
        }
        
        // Progress update every 100 files
        if ($progress % 100 === 0) {
            echo " - ✅ $uploaded uploaded, ❌ $failed failed";
        }
    }
    
    echo "\n\n";
    
    // Step 5: Cleanup
    echo "🧹 Step 5: Cleaning up...\n";
    
    unlink($localArchive);
    exec("rm -rf $extractPath");
    
    echo "✅ Cleanup complete\n\n";
    
    // Summary
    $duration = round(microtime(true) - $startTime, 2);
    
    echo str_repeat("=", 50) . "\n";
    echo "📊 MIGRATION SUMMARY\n";
    echo str_repeat("=", 50) . "\n";
    echo "Total files:    $totalFiles\n";
    echo "✅ Uploaded:    $uploaded\n";
    echo "❌ Failed:      $failed\n";
    echo "⏱️  Duration:    {$duration}s\n";
    echo str_repeat("=", 50) . "\n";
    
    if ($failed > 0) {
        echo "\n❌ Failed files:\n";
        foreach (array_slice($failedFiles, 0, 20) as $fail) {
            echo "  - {$fail['file']}: {$fail['error']}\n";
        }
        if (count($failedFiles) > 20) {
            echo "  ... and " . (count($failedFiles) - 20) . " more\n";
        }
    }
    
    echo "\n🎉 Migration complete!\n";
    echo "All images are now available at:\n";
    echo "https://s3.twcstorage.ru/$bucketName/uploaded/...\n";
    
} catch (Exception $e) {
    echo "\n\n❌ ERROR: {$e->getMessage()}\n";
    echo "Trace: {$e->getTraceAsString()}\n";
    exit(1);
}

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
