<?php
require __DIR__.'/vendor/autoload.php';

use Aws\S3\S3Client;

$s3Client = new S3Client([
    'version' => 'latest',
    'region' => 'ru-1',
    'endpoint' => 'https://s3.twcstorage.ru',
    'use_path_style_endpoint' => false,
    'credentials' => [
        'key' => getenv('S3_ACCESS_KEY'),
        'secret' => getenv('S3_SECRET_KEY'),
    ],
]);

try {
    echo "Testing S3 with use_path_style_endpoint=false...\n\n";
    
    $result = $s3Client->listObjects([
        'Bucket' => '46885a37-67c8e067-4002-4498-a06b-cb98beB07ea3',
        'MaxKeys' => 3,
    ]);
    echo "✅ Bucket accessible! Found " . count($result['Contents'] ?? []) . " objects\n\n";
    
    echo "Uploading test file...\n";
    $result = $s3Client->putObject([
        'Bucket' => '46885a37-67c8e067-4002-4498-a06b-cb98beB07ea3',
        'Key' => 'test-replit.txt',
        'Body' => 'Test from Replit: ' . date('Y-m-d H:i:s'),
        'ACL' => 'public-read',
    ]);
    echo "✅ Upload successful!\n";
    echo "URL: " . $result['ObjectURL'] . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
