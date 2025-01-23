FROM alpine:3.19 as base

RUN apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted gnu-libiconv \
    && apk add -U --no-cache \
    # Packages \
    php83 \
    php83-dev \
    php83-common \
    php83-gd \
    php83-xmlreader \
    php83-bcmath \
    php83-ctype \
    php83-curl \
    php83-exif \
    php83-iconv \
    php83-intl \
    php83-json \
    php83-mbstring \
    php83-opcache \
    php83-openssl \
    php83-pcntl \
    php83-pdo \
    php83-mysqlnd \
    php83-pdo_mysql \
    php83-pdo_pgsql \
    php83-phar \
    php83-posix \
    php83-session \
    php83-xml \
    php83-xsl \
    php83-zip \
    php83-zlib \
    php83-dom \
    php83-redis \
    php83-fpm \
    php83-sodium \
    php83-tokenizer \
    php83-xmlwriter \
    php83-simplexml \
    php83-pecl-ast \
    # Iconv Fix
    && apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted php83-pecl-apcu \
    && ln -s /usr/bin/php83 /usr/bin/php

ADD etc/artifacts/rootfs /

CMD ["/usr/sbin/php-fpm83", "-R", "--nodaemonize"]

EXPOSE 9000

WORKDIR /app

FROM base as dev

ARG COMPOSER_VERSION=2.2.7

RUN apk add -U --no-cache \
        php83-pear \
        openssh \
        supervisor \
        autoconf \
        git \
        curl \
        wget \
        make \
        zip \
        php83-xdebug \
    && rm -rf /var/cache/apk/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer --version=${COMPOSER_VERSION}

ADD etc/artifacts/devfs /

EXPOSE 9000
