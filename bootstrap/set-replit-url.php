<?php

$envPath = __DIR__ . '/../.env';

if (!file_exists($envPath)) {
    echo "Warning: .env file not found\n";
    exit(0);
}

$replitDomain = getenv('REPLIT_DEV_DOMAIN') ?: getenv('REPLIT_DOMAINS');

if (!$replitDomain) {
    echo "Not running on Replit, skipping APP_URL update\n";
    exit(0);
}

$appUrl = "https://{$replitDomain}";

$envContent = file_get_contents($envPath);

$updated = preg_replace(
    '/^APP_URL=.*/m',
    "APP_URL={$appUrl}",
    $envContent
);

// В Replit используем PostgreSQL для быстрой разработки
$updated = preg_replace(
    '/^DB_CONNECTION=.*/m',
    "DB_CONNECTION=pgsql",
    $updated
);

if ($updated !== $envContent) {
    file_put_contents($envPath, $updated);
    echo "Updated APP_URL to: {$appUrl}\n";
    echo "Set DB_CONNECTION to: pgsql (Replit PostgreSQL)\n";
} else {
    echo "APP_URL already set correctly\n";
}
