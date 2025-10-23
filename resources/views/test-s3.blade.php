<!DOCTYPE html>
<html>
<head>
    <title>S3 Storage Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-item { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .test-item img { max-width: 300px; display: block; margin-top: 10px; }
        .status { padding: 5px 10px; border-radius: 3px; display: inline-block; }
        .status.success { background: #d4edda; color: #155724; }
        .status.error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <h1>🧪 S3 Storage Test</h1>
    <p><strong>Default Disk:</strong> {{ config('filesystems.default') }}</p>
    <p><strong>Endpoint:</strong> {{ config('filesystems.disks.s3.endpoint') }}</p>
    
    <hr>
    
    @foreach($results as $result)
        <div class="test-item">
            <h3>{{ $result['path'] }}</h3>
            <p><strong>Exists:</strong> 
                <span class="status {{ $result['exists'] ? 'success' : 'error' }}">
                    {{ $result['exists'] ? 'YES' : 'NO' }}
                </span>
            </p>
            <p><strong>URL:</strong> <a href="{{ $result['url'] }}" target="_blank">{{ $result['url'] }}</a></p>
            
            @if($result['exists'])
                <img src="{{ $result['url'] }}" alt="{{ $result['path'] }}">
            @endif
        </div>
    @endforeach
</body>
</html>
