#!/bin/bash

set -e
echo "Checking for .env file..."
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        echo "Copying .env.example to .env..."
        cp .env.example .env
    else
        echo "Error: .env.example does not exist. Please create one."
        exit 1
    fi
fi

echo "Checking for vendor directory..."
if [ ! -d "vendor" ]; then
    echo "Installing dependencies with Composer..."
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w "/var/www/html" \
        laravelsail/php82-composer:latest \
        composer install
fi

echo "Starting Docker containers..."
docker-compose up -d

echo "Starting Laravel Sail..."
./vendor/bin/sail up -d

echo "Running migrations..."
./vendor/bin/sail artisan migrate

echo "Running migrations..."
./vendor/bin/sail artisan key:generate

echo "Starting queue worker..."
./vendor/bin/sail artisan queue:work --daemon
