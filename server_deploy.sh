#!/bin/sh
set -e
 
echo "Deploying application ..."
  # Update codebase
    git pull origin main
 
    # Install dependencies based on lock file
    composer install --no-interaction --prefer-dist --optimize-autoloader
 
    # Migrate database
    php artisan migrate --force
    #For generating Swagger Docs
    #php artisan l5-swagger:generate
    # Note: If you're using queue workers, this is the place to restart them.
    # ...
 
    # Clear cache
    php artisan optimize


echo "Application deployed!"
