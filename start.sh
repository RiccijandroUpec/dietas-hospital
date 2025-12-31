#!/bin/bash

echo "🚀 Starting Railway deployment..."

# Run migrations
echo "📦 Running migrations..."
php artisan migrate --force --no-interaction

# Create sessions table if it doesn't exist
echo "🔐 Ensuring sessions table exists..."
php artisan session:table 2>/dev/null || true
php artisan migrate --force --no-interaction

# Seed users if needed
echo "👥 Seeding users..."
php artisan db:seed --class=UsersSeeder --force --quiet 2>/dev/null || echo "⚠️  Seeder skipped (might already exist)"

# Reset passwords for existing users
echo "🔑 Resetting user passwords..."
php artisan users:reset-passwords --quiet 2>/dev/null || echo "⚠️  Password reset skipped"

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if it doesn't exist
echo "🔗 Creating storage link..."
php artisan storage:link 2>/dev/null || echo "⚠️  Storage link already exists"

# Start Nginx with PHP-FPM
echo "🌐 Starting web server..."
echo "✅ Deployment complete!"

# Start PHP built-in server as fallback
# In production, Railway will use Nginx, but this ensures compatibility
exec php -S 0.0.0.0:${PORT:-8080} -t public
