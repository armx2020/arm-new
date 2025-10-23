<?php
require __DIR__.'/vendor/autoload.php';

use Aws\S3\S3Client;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

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

$bucket = '46885a37-67c8e067-4002-4498-a06b-cb98be807ea3';
$localDir = $argv[1] ?? './uploaded';

if (!is_dir($localDir)) {
    echo "❌ Папка не найдена: $localDir\n";
    exit(1);
}

echo "🚀 Загружаем файлы из: $localDir\n";
echo "📦 Бакет: $bucket\n\n";

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($localDir),
    RecursiveIteratorIterator::LEAVES_ONLY
);

$stats = ['total' => 0, 'success' => 0, 'failed' => 0];

foreach ($files as $file) {
    if ($file->isDir()) continue;
    
    $localPath = $file->getRealPath();
    $relativePath = str_replace($localDir . '/', '', $localPath);
    $s3Key = 'uploaded/' . $relativePath;
    
    $stats['total']++;
    
    try {
        $s3Client->putObject([
            'Bucket' => $bucket,
            'Key' => $s3Key,
            'Body' => fopen($localPath, 'r'),
            'ACL' => 'public-read',
        ]);
        $stats['success']++;
        
        if ($stats['success'] % 50 === 0) {
            echo "✅ Загружено: {$stats['success']} / {$stats['total']}\n";
        }
    } catch (Exception $e) {
        $stats['failed']++;
        echo "❌ Ошибка: $relativePath - " . $e->getMessage() . "\n";
    }
}

echo "\n=== Готово! ===\n";
echo "✅ Успешно: {$stats['success']}\n";
echo "❌ Ошибок: {$stats['failed']}\n";
echo "📦 Всего: {$stats['total']}\n";
