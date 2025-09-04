#!/bin/sh
set -e

# This script is executed on container startup.
# It checks the CONTAINER_ROLE environment variable to determine
# if it should run setup tasks like migrations and tests.

# The 'app' service is designated to run these tasks.
if [ "$CONTAINER_ROLE" = "app" ]; then
    echo "Running as [app] container. Performing setup tasks..."

    # Install Composer dependencies
    echo "Installing Composer dependencies..."
    composer install --no-interaction --no-progress --prefer-dist

    # Generate application key if it doesn't exist
    php artisan key:generate --ansi

    # Run database migrations
    echo "Running database migrations..."
    php artisan migrate --force
    echo "Database migrations completed."

    # Run Swagger generation
    echo "Generating Swagger documentation..."
    php artisan l5-swagger:generate
    echo "Swagger documentation generated."

    echo "Setup tasks completed."
else
    echo "Running as [${CONTAINER_ROLE}] container. Skipping setup tasks."
fi

# Execute the main command passed to the entrypoint (e.g., "php artisan serve" or "php artisan queue:work")
exec "$@"