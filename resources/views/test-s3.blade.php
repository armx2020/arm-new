<!DOCTYPE html>
<html>
<head>
    <title>S3 Configuration Test</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .info { background: white; padding: 20px; margin: 10px 0; border-radius: 5px; }
        .ok { color: green; }
        .error { color: red; }
        h2 { margin-top: 0; }
        pre { background: #eee; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔍 S3 Configuration Test</h1>
    
    <div class="info">
        <h2>📅 Deployment Info</h2>
        <p><strong>Last Deploy:</strong> {{ now()->toDateTimeString() }}</p>
        <p><strong>Environment:</strong> {{ app()->environment() }}</p>
    </div>

    <div class="info">
        <h2>💾 Filesystem Configuration</h2>
        <p><strong>Default Disk:</strong> <span class="{{ config('filesystems.default') === 's3' ? 'ok' : 'error' }}">{{ config('filesystems.default') }}</span></p>
        <p><strong>S3 Configured:</strong> <span class="{{ config('filesystems.disks.s3.key') ? 'ok' : 'error' }}">{{ config('filesystems.disks.s3.key') ? 'Yes' : 'No' }}</span></p>
    </div>

    <div class="info">
        <h2>🔑 S3 Settings (без секретов)</h2>
        <pre>Region: {{ config('filesystems.disks.s3.region') }}
Bucket: {{ config('filesystems.disks.s3.bucket') }}
Endpoint: {{ config('filesystems.disks.s3.endpoint') }}
URL: {{ config('filesystems.disks.s3.url') }}
Key configured: {{ config('filesystems.disks.s3.key') ? 'YES ✓' : 'NO ✗' }}
Secret configured: {{ config('filesystems.disks.s3.secret') ? 'YES ✓' : 'NO ✗' }}</pre>
    </div>

    <div class="info">
        <h2>🖼️ Test Image URL</h2>
        <p>Sample path: uploaded/test.jpg</p>
        <p><strong>Generated URL:</strong></p>
        <pre>{{ \App\Helpers\StorageHelper::imageUrl('uploaded/test.jpg') }}</pre>
    </div>

    <div class="info">
        <h2>📝 Latest Git Commit</h2>
        <pre>{{ shell_exec('git log -1 --oneline 2>&1') ?: 'Git info not available' }}</pre>
    </div>

    <div class="info">
        <h2>✅ Checklist</h2>
        <ul>
            <li class="{{ config('filesystems.default') === 's3' ? 'ok' : 'error' }}">Default disk is S3: {{ config('filesystems.default') === 's3' ? 'YES' : 'NO' }}</li>
            <li class="{{ config('filesystems.disks.s3.key') ? 'ok' : 'error' }}">S3 credentials configured: {{ config('filesystems.disks.s3.key') ? 'YES' : 'NO' }}</li>
            <li class="{{ class_exists('App\Helpers\ImageUploadHelper') ? 'ok' : 'error' }}">ImageUploadHelper exists: {{ class_exists('App\Helpers\ImageUploadHelper') ? 'YES' : 'NO' }}</li>
        </ul>
    </div>
</body>
</html>
