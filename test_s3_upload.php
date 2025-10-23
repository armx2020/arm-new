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

$bucket = '46885a37-67c8e067-4002-4498-a06b-cb98be807ea3';

try {
    echo "✅ Загружаю тестовый файл...\n";
    $result = $s3Client->putObject([
        'Bucket' => $bucket,
        'Key' => 'test-from-replit.txt',
        'Body' => 'Тест из Replit: ' . date('Y-m-d H:i:s'),
        'ACL' => 'public-read',
    ]);
    echo "✅ Файл загружен!\n";
    echo "URL: https://s3.twcstorage.ru/" . $bucket . "/test-from-replit.txt\n\n";
    
    echo "✅ Читаю файл обратно...\n";
    $result = $s3Client->getObject([
        'Bucket' => $bucket,
        'Key' => 'test-from-replit.txt',
    ]);
    echo "Содержимое: " . $result['Body'] . "\n";
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
}
