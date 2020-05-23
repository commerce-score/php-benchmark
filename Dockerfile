FROM ubuntu:18.04

ADD benchmark.php /benchmark.php

RUN export ENV PHP=7.0 && \
    export BUILD_PACKAGES=gnupg && \
    export DEBIAN_FRONTEND=noninteractive && \
    apt-get update && \
    apt-get -y install --no-install-recommends $BUILD_PACKAGES && \
    apt-get -y dist-upgrade && \
    apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 14AA40EC0831756756D7F66C4F4EA0AAE5267A6C && \
    echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu bionic main" >> /etc/apt/sources.list && \
    apt-get update && \
    apt-get -y install --no-install-recommends ca-certificates \
    php${PHP}-cli \
    php${PHP}-gmp \
    php${PHP}-bcmath \
    php${PHP}-bz2 \
    php${PHP}-curl \
    php${PHP}-gd \
    php${PHP}-intl \
    php${PHP}-json \
    php${PHP}-mbstring \
    php${PHP}-mysql \
    php${PHP}-opcache \
    php${PHP}-readline \
    php${PHP}-soap \
    php${PHP}-sqlite3 \
    php${PHP}-xml \
    php${PHP}-zip && \
    apt-get -y purge $BUILD_PACKAGES && \
    apt-get -y autoremove && \
    apt-get -y clean && \
    rm -rf /var/lib/apt/lists/*

CMD php benchmark.php
