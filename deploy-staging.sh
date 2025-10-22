#!/bin/bash

set -e

echo "🚀 Starting STAGING deployment..."

# Navigate to project directory
cd /var/www/staging.vsearmyne.ru

# Pull latest code from GitHub
echo "📥 Pulling latest code from GitHub..."
git pull origin main

# Install/update dependencies
echo "📦 Installing dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Clear and cache configuration
echo "⚙️  Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run migrations
echo "🗄️  Running migrations..."
php artisan migrate --force

# Optimize for production
echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Restart PHP-FPM (опционально, если используете PHP-FPM)
echo "🔄 Restarting PHP-FPM..."
systemctl reload php8.3-fpm || true

echo "✅ STAGING deployment completed successfully!"
