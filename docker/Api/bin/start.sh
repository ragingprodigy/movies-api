#!/usr/bin/env bash
set -e
cd /var/www/application/

role=${CONTAINER_ROLE:-app}
queue_name=${QUEUE_NAME:-default}
artisan_cmd=${ARTISAN_CMD:-help}

echo "Container Role is \"$role\"..."

echo ${START_MESSAGE}

if [[ "$role" = "queue" ]]; then

    echo "Waiting 5 seconds before starting queue processing..."

    # Wait 5 seconds before starting to process queue
    sleep 5

    echo "Running \"$queue_name\" queue..."
    php /var/www/application/artisan queue:work --queue="$queue_name" --verbose --tries=2 --timeout=0 --sleep=5

elif [[ "$role" = "app" ]]; then

    echo "Starting web app..."
    exec php-fpm -RF

elif [[ "$role" = "scheduler" ]]; then

    while [[ true ]]
    do
      php /var/www/application/artisan schedule:run --verbose --no-interaction &
      sleep 60
    done

else
    echo "Could not match the container role \"$role\""
    exit 1
fi
