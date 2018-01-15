FROM alpine:3.6

MAINTAINER Peng Yue <penyue@gmail.com>

# Used only to force cache invalidation
ARG CACHE_BUSTER=2017-06-25-A

# Setup a simple and informative shell prompt
ENV PS1='\u@\h.${POD_NAMESPACE}:/\W \$ '

# Add a user 'build' to run the build and a user 'www' to run the app
RUN addgroup -g 9998 -S build && adduser -u 9998 -G build -S build && \
 addgroup -g 9999 -S www && adduser -u 9999 -G www -S www -H

# Install required packages
RUN apk upgrade --no-cache && \
 apk add --no-cache s6 bind-tools libcap ca-certificates openssl openssh bash git socat strace jq nano curl rsync mysql-client \
 nginx \
 php7-bcmath \
 php7-bz2 \
 php7-calendar \
 php7-ctype \
 php7-curl \
 php7-dom \
 php7-exif \
 php7-fpm \
 php7-ftp \
 php7-gd \
 php7-gettext \
 php7-gmp \
 php7-iconv \
 php7-json \
 php7-mbstring \
 php7-mcrypt \
 php7-opcache \
 php7-openssl \
 php7-pcntl \
 php7-pdo_mysql \
 php7-pdo_sqlite \
 php7-phar \
 php7-posix \
 php7-session \
 php7-simplexml \
 php7-soap \
 php7-sockets \
 php7-sqlite3 \
 php7-tokenizer \
 php7-wddx \
 php7-xdebug \
 php7-xml \
 php7-xmlreader \
 php7-xmlrpc \
 php7-xmlwriter \
 php7-xsl \
 php7-zip \
 php7-zlib && \
 rm -rf /etc/php7/conf.d/xdebug.ini && \
 ln -s /usr/sbin/php-fpm7 /usr/sbin/php-fpm

# Install composer
RUN wget -qO /usr/local/bin/composer https://getcomposer.org/download/1.4.2/composer.phar && chmod +x /usr/local/bin/composer

# Install composer parallel install plugin
USER build
RUN composer global require "hirak/prestissimo:^0.3" --no-interaction --no-ansi --quiet --no-progress --prefer-dist && composer clear-cache --no-ansi --quiet && chmod -R go-w ~/.composer/vendor/
USER root

# Set the working directory to /app
WORKDIR /app

# Set a trigger to purge the sample app on descendants
ONBUILD RUN rm -rf /app/public/*
ONBUILD RUN rm -rf /app/var/cache/*


# Add app
COPY . /app

# Set permissions for build
RUN mkdir -p /app/vendor/ && \
    chown -R build:build /app/vendor/ && \
    mkdir -p /app/var && \
    chown -R build:build /app/var/ && \
    mkdir -p /app/var/cache/ && \
    chown -R build:build /app/var/cache/ && \
    chown -R build:build /app/bin/

# Run composer install as user 'build' and clean up the cache
USER build
RUN php bin/console cache:clear --no-warmup
RUN composer install --no-interaction --no-ansi --no-progress --prefer-dist && composer clear-cache --no-ansi --quiet
USER root

# Fix permissions
RUN chown -R root:root /app/vendor/ && \
    chmod -R go-w /app/vendor/ && \
    chown -R www:www /app/var/cache/ && \
    chown -R www:www /app/var/storage/ && \
    chown -R www:www /app/bin/
