#!/bin/bash
set -e

echo "🚀 Starting deployment..."

APP_DIR="/var/www/food-summit"
PHP_BIN="/usr/bin/php"
COMPOSER_BIN="/usr/local/bin/composer"

cd $APP_DIR

echo "📥 Pulling latest code..."
git pull origin main

echo "🔐 Setting correct permissions (pre-install)..."
# Make deploy user the owner, nginx the group
sudo chown -R deploy:nginx storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo "📦 Installing PHP dependencies..."
$COMPOSER_BIN install --no-dev --optimize-autoloader

echo "📦 Installing Node dependencies..."
npm install

echo "🏗️ Building frontend assets..."
npm run build

echo "🗄️ Running database migrations..."
$PHP_BIN artisan migrate --force

echo "🧹 Clearing & optimizing Laravel caches..."
$PHP_BIN artisan optimize:clear
$PHP_BIN artisan optimize

echo "🔐 Setting correct runtime permissions..."
# Keep deploy as owner for future deployments, nginx as group for web server
sudo chown -R deploy:nginx storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo "🔄 Restarting services..."
sudo systemctl restart nginx
sudo systemctl restart php-fpm || sudo systemctl restart php8.2-fpm

echo "✅ Deployment finished successfully!"
