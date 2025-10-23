<?php
require __DIR__.'/vendor/autoload.php';

use Aws\S3\S3Client;

$s3Client = new S3Client([
    'version' => 'latest',
    'region' => 'ru-1',
    'endpoint' => 'https://s3.twcstorage.ru',
    'credentials' => [
        'key' => getenv('S3_ACCESS_KEY'),
        'secret' => getenv('S3_SECRET_KEY'),
    ],
]);

try {
    echo "Список доступных бакетов:\n\n";
    $result = $s3Client->listBuckets();
    foreach ($result['Buckets'] as $bucket) {
        echo "- " . $bucket['Name'] . " (создан: " . $bucket['CreationDate']->format('Y-m-d H:i:s') . ")\n";
    }
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
}
