#
# Stage 1 - Prep App's PHP Dependencies
#
FROM composer:2.1 as vendor

WORKDIR /app

COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --quiet

# end Stage 1 #

#
# Stage 2 - Prep App's Frontend CSS & JS
#
FROM node:14-alpine as frontend

COPY package.json webpack.config.js yarn.lock /app/
COPY ./assets/ /app/assets/


WORKDIR /app

RUN yarn install --silent \
    && yarn encore production


# end Stage 2 #




FROM php:8.0-fpm-alpine as phpserver

# add cli tools
RUN apk update \
    && apk upgrade \    
    && apk add nginx

    
# silently install 'docker-php-ext-install' extensions
RUN set -x

RUN docker-php-ext-install pdo_mysql bcmath > /dev/null

COPY nginx.conf /etc/nginx/nginx.conf

COPY php.ini /usr/local/etc/php/conf.d/local.ini
RUN cat /usr/local/etc/php/conf.d/local.ini

WORKDIR /var/www

COPY . /var/www/
COPY --from=vendor /app/vendor /var/www/vendor
COPY --from=frontend /app/public/build /var/www/public/build

##RUN mkdir /var/www/var
##RUN chown -R www-data:www-data /var/www/var

EXPOSE 80

COPY docker-entry.sh /etc/entrypoint.sh
ENTRYPOINT ["sh", "/etc/entrypoint.sh"]
