#!/bin/sh
set -e

echo "🚀 Starting Laravel application..."

# Function to check database connection
check_db() {
    php -r "
        try {
            \$pdo = new PDO(
                'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD'),
                [PDO::ATTR_TIMEOUT => 3]
            );
            exit(0);
        } catch (Exception \$e) {
            exit(1);
        }
    " 2>/dev/null
}

# Wait for database
echo "⏳ Waiting for MySQL database..."
for i in $(seq 1 20); do
    if check_db; then
        echo "✅ Database connection successful!"
        break
    fi

    if [ $i -eq 20 ]; then
        echo "❌ Could not connect to database after 20 attempts"
        exit 1
    fi

    echo "   Attempt $i/20 - waiting 3 seconds..."
    sleep 3
done

# Run migrations
echo "📦 Running database migrations..."
php artisan migrate --force

# Create storage link
echo "🔗 Creating storage symlink..."
php artisan storage:link || true

# Optimize application
echo "⚡ Optimizing application..."
php artisan optimize

# تحديد الـ PORT (مهم جداً!)
PORT=${PORT:-8080}

# Start server
echo "🌐 Starting web server on 0.0.0.0:${PORT}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT}
