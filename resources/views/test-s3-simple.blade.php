<!DOCTYPE html>
<html>
<head>
    <title>S3 Storage Test - Simple</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        h1 { color: #333; }
        .test-item { margin: 20px 0; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .test-item h3 { margin-top: 0; color: #666; font-size: 14px; }
        .test-item img { max-width: 400px; height: auto; display: block; margin-top: 15px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
        .url { word-break: break-all; color: #0066cc; text-decoration: none; font-size: 12px; display: block; margin: 10px 0; }
        .info { background: #e3f2fd; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>🧪 S3 Storage Test - Загруженные картинки</h1>
    
    <div class="info">
        <strong>Default Disk:</strong> {{ config('filesystems.default') }}<br>
        <strong>S3 Endpoint:</strong> {{ config('filesystems.disks.s3.endpoint') }}<br>
        <strong>S3 Bucket:</strong> {{ config('filesystems.disks.s3.bucket') }}
    </div>
    
    @foreach($results as $result)
        <div class="test-item">
            <h3>📁 {{ $result['path'] }}</h3>
            <a href="{{ $result['url'] }}" target="_blank" class="url">{{ $result['url'] }}</a>
            <img src="{{ $result['url'] }}" alt="{{ $result['path'] }}" onerror="this.alt='❌ Ошибка загрузки'; this.style.display='none'; this.nextElementSibling.style.display='block';">
            <div style="display:none; color: red; padding: 10px; background: #fee; border-radius: 4px;">❌ Не удалось загрузить изображение</div>
        </div>
    @endforeach
</body>
</html>
