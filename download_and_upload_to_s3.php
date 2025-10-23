<?php
set_time_limit(0);
require __DIR__.'/vendor/autoload.php';

use Aws\S3\S3Client;

$s3 = new S3Client([
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
$prodUrl = 'https://vsearmyane.ru/storage';

// Bootstrap Laravel
require __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$limit = isset($argv[1]) ? (int)$argv[1] : 100;
$images = DB::table('images')->whereNotNull('path')->limit($limit)->get();

echo "🚀 Загружаем {$images->count()} картинок в S3...\n\n";

$stats = ['success' => 0, 'failed' => 0, 'skipped' => 0];

foreach ($images as $index => $image) {
    if (empty($image->path)) {
        $stats['skipped']++;
        continue;
    }
    
    $url = $prodUrl . '/' . $image->path;
    
    try {
        $content = @file_get_contents($url);
        if ($content === false) {
            $stats['failed']++;
            echo "❌ Не скачалось: {$image->path}\n";
            continue;
        }
        
        $s3->putObject([
            'Bucket' => $bucket,
            'Key' => $image->path,
            'Body' => $content,
            'ACL' => 'public-read',
        ]);
        
        $stats['success']++;
        if ($stats['success'] % 10 === 0) {
            echo "✅ {$stats['success']}/{$images->count()}\n";
        }
    } catch (Exception $e) {
        $stats['failed']++;
        echo "❌ {$image->path}: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Готово! ===\n";
echo "✅ Загружено: {$stats['success']}\n";
echo "❌ Ошибок: {$stats['failed']}\n";
echo "⏭️  Пропущено: {$stats['skipped']}\n";
