#
# PHP Dependencies
#
FROM composer:1.10.5 as vendor
COPY database/ database/
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

#
# Frontend
#
FROM node:11.15 as frontend
RUN mkdir -p /app/public
COPY package.json webpack.mix.js yarn.lock /app/
COPY resources/assets /app/resources/assets
WORKDIR /app
RUN yarn install && yarn production

#
# Application
#
# based on: See: https://github.com/jtreminio/php-docker and https://jtreminio.com/
#
FROM jtreminio/php-nginx:7.4

ARG APP_CODE_PATH_CONTAINER=/var/www

COPY . ${APP_CODE_PATH_CONTAINER}
COPY --from=vendor /app/vendor/ ${APP_CODE_PATH_CONTAINER}/vendor/
COPY --from=frontend /app/public/js/ ${APP_CODE_PATH_CONTAINER}/public/js/
COPY --from=frontend /app/public/css/ ${APP_CODE_PATH_CONTAINER}/public/css/
COPY --from=frontend /app/mix-manifest.json ${APP_CODE_PATH_CONTAINER}/mix-manifest.json

WORKDIR ${APP_CODE_PATH_CONTAINER}
RUN chown -R www-data:www-data ${APP_CODE_PATH_CONTAINER} \
        php artisan config:cache && \
        php artisan route:cache && \
        php artisan view:cache