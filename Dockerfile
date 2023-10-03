FROM php:8.1.5-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_DISABLE_XDEBUG_WARN=1 \
    PHPREDIS_VERSION=5.1.1
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

RUN set -xe \
    && apk add --no-cache --virtual .build-deps \
    tzdata \
    $PHPIZE_DEPS \
    && apk add gnu-libiconv --update-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted \
    && cp /usr/share/zoneinfo/UTC /etc/localtime \
    && echo 'UTC' > /etc/localtime \

    && apk add --no-cache \
    openssl-dev \
    bash \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    sqlite-dev \
    curl \
    curl-dev \
    libsodium-dev \
    icu-dev \
    libxml2-dev \
    recode-dev \
    libxslt-dev \
    git \
    nodejs \
    npm \
    supervisor \
    postgresql-client \
    postgresql-dev \
    openssh-client \
    libmcrypt-dev \
    libmcrypt \
    libzip-dev \
    libgcrypt-dev \
    oniguruma-dev \
    && apk --update --no-cache add grep \
    && mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    icu-dev \
    openssl-dev \
    && docker-php-ext-configure gd \
    --with-freetype=/usr/include/ \

    && docker-php-ext-install -j$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1)\
    gd \
    bcmath \
    opcache \
    mysqli \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    pdo_pgsql \
    zip \
    xml \
    xsl \
    intl \
    mbstring \
    curl \
    simplexml \
    soap \
    bcmath \
    shmop \
    sockets \
    sysvmsg \
    sysvsem \
    sysvshm \

    #Enable PHP extensions
    && docker-php-ext-install sodium \
    && apk del .build-deps \
    && rm -rf /tmp/* /var/cache/apk/*

COPY start.sh /
COPY php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY php/supervisord.conf /etc/supervisord.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN npm i -g pm2

RUN addgroup -S sudo \
    && adduser -h /home/web -u 1000 -S -s /bin/bash -G sudo web \
    && echo "%sudo ALL=NOPASSWD:ALL" > /etc/sudoers

RUN chmod +x /start.sh

# Setup working directory
WORKDIR /var/www

EXPOSE 9000
CMD ["/start.sh"]
