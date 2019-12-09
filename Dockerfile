FROM ragingprodigy/docker-php7.3-fpm:latest

ENV TERM dump
ENV START_MESSAGE Application has been installed
ENV LOCK_FILE_PATH /tmp/application.lock

COPY ./docker/Api/bin /usr/local/bin/app
COPY . /var/www/application

ARG IS_DEV_MODE

RUN chmod +x /usr/local/bin/app/* && chown -R www-data:www-data /var/www/application/storage

RUN sed -i "s/www-data/root/g" /usr/local/etc/php-fpm.d/www.conf
RUN php -d memory_limit=-1 /usr/local/bin/composer install \
        --no-ansi \
        --no-dev \
        --prefer-dist \
        --no-interaction \
        --no-progress \
        --no-scripts \
        --optimize-autoloader \
        --working-dir \
            /var/www/application \
    && \
    touch /var/www/application/storage/logs/lumen.log && chmod 777 /var/www/application/storage/logs/lumen.log

WORKDIR /var/www/application

RUN printf "alias art='php artisan'\n" >> /root/.bashrc

RUN if [ -n "$IS_DEV_MODE" ]; then echo "Dev Mode... Skipping Docker and Git Cleanup"; else rm -rf ./docker/ ./git/ ; fi
RUN if [ -n "$IS_DEV_MODE" ]; then echo "Dev Mode... Skipping Docker and build script Cleanup"; else exec rm ./Dockerfile ./build.sh .editorconfig ; fi

CMD /usr/local/bin/app/run.sh
