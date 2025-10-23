<?php

require __DIR__.'/vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$ftpHost = '213.139.208.16';
$ftpUser = 'root';
$ftpPass = 'jM@c3@TJHZvW4z';
$ftpBasePath = '/var/www/www/html/arm/storage/app/public';

$s3AccessKey = getenv('S3_ACCESS_KEY') ?: getenv('AWS_ACCESS_KEY_ID');
$s3SecretKey = getenv('S3_SECRET_KEY') ?: getenv('AWS_SECRET_ACCESS_KEY');
$s3Bucket = '46885a37-67c8e067-4002-4498-a06b-cb98be807ea3';
$s3Endpoint = 'https://s3.timeweb.cloud';
$s3Region = 'ru-1';

if (!$s3AccessKey || !$s3SecretKey) {
    die("❌ S3 credentials not found! Set S3_ACCESS_KEY and S3_SECRET_KEY in Replit Secrets.\n");
}

echo "🚀 Starting FTP → S3 Direct Migration\n";
echo "=====================================\n\n";

$s3Client = new S3Client([
    'version' => 'latest',
    'region'  => $s3Region,
    'endpoint' => $s3Endpoint,
    'use_path_style_endpoint' => true,
    'credentials' => [
        'key'    => $s3AccessKey,
        'secret' => $s3SecretKey,
    ],
]);

echo "📡 Connecting to FTP server $ftpHost...\n";
$ftpConn = ftp_connect($ftpHost);
if (!$ftpConn) {
    die("❌ Failed to connect to FTP server\n");
}

if (!ftp_login($ftpConn, $ftpUser, $ftpPass)) {
    die("❌ Failed to login to FTP server\n");
}

ftp_pasv($ftpConn, true);
echo "✅ Connected to FTP server\n\n";

function getFtpFileList($ftpConn, $dir) {
    $files = [];
    $list = ftp_nlist($ftpConn, $dir);
    
    if ($list === false) {
        return $files;
    }
    
    foreach ($list as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        $fullPath = $item;
        
        $size = ftp_size($ftpConn, $fullPath);
        if ($size != -1) {
            $files[] = $fullPath;
        } else {
            $subFiles = getFtpFileList($ftpConn, $fullPath);
            $files = array_merge($files, $subFiles);
        }
    }
    
    return $files;
}

echo "📂 Scanning directories: uploaded/ and users/\n";
echo "Please wait, this may take a minute...\n\n";

$uploadedFiles = getFtpFileList($ftpConn, $ftpBasePath . '/uploaded');
$usersFiles = getFtpFileList($ftpConn, $ftpBasePath . '/users');

$allFiles = array_merge($uploadedFiles, $usersFiles);
$totalFiles = count($allFiles);

echo "✅ Found $totalFiles files to migrate\n";
echo "=====================================\n\n";

if ($totalFiles === 0) {
    die("❌ No files found to migrate\n");
}

$confirm = readline("⚠️  Start migration of $totalFiles files? (yes/no): ");
if (strtolower($confirm) !== 'yes') {
    die("❌ Migration cancelled\n");
}

echo "\n🔄 Starting migration...\n\n";

$uploaded = 0;
$failed = 0;
$startTime = time();

foreach ($allFiles as $ftpFile) {
    $relativePath = str_replace($ftpBasePath . '/', '', $ftpFile);
    
    $tempFile = tmpfile();
    $tempPath = stream_get_meta_data($tempFile)['uri'];
    
    if (ftp_get($ftpConn, $tempPath, $ftpFile, FTP_BINARY)) {
        try {
            $s3Client->putObject([
                'Bucket' => $s3Bucket,
                'Key'    => $relativePath,
                'SourceFile' => $tempPath,
                'ACL'    => 'public-read',
            ]);
            
            $uploaded++;
            $percent = round(($uploaded / $totalFiles) * 100, 1);
            echo "✅ [$uploaded/$totalFiles] ($percent%) $relativePath\n";
            
        } catch (AwsException $e) {
            $failed++;
            echo "❌ Failed: $relativePath - " . $e->getMessage() . "\n";
        }
    } else {
        $failed++;
        echo "❌ FTP download failed: $relativePath\n";
    }
    
    fclose($tempFile);
    
    if ($uploaded % 100 === 0 && $uploaded > 0) {
        $elapsed = time() - $startTime;
        $perFile = $elapsed / $uploaded;
        $remaining = ($totalFiles - $uploaded) * $perFile;
        $eta = gmdate("H:i:s", $remaining);
        echo "\n⏱️  Progress: $uploaded/$totalFiles | ETA: $eta\n\n";
    }
}

ftp_close($ftpConn);

$elapsed = time() - $startTime;
$elapsedFormatted = gmdate("H:i:s", $elapsed);

echo "\n=====================================\n";
echo "✅ Migration completed!\n";
echo "📊 Statistics:\n";
echo "   - Total files: $totalFiles\n";
echo "   - Uploaded: $uploaded\n";
echo "   - Failed: $failed\n";
echo "   - Time: $elapsedFormatted\n";
echo "=====================================\n";
