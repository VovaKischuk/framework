FROM ubuntu:jammy AS cli

# Fixes some weird terminal issues such as broken clear / CTRL+L
ENV TERM=linux

# Ensure apt doesn't ask questions when installing stuff
ENV DEBIAN_FRONTEND=noninteractive

# Install Ondrej repos for Ubuntu jammy, PHP, composer and selected extensions - better selection than
# the distro's packages
RUN apt-get update \
    && apt-get install -y --no-install-recommends gnupg software-properties-common \
    && add-apt-repository ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        git \
        unzip \
        php8.3-apcu \
        php8.3-cli \
        php8.3-curl \
        php8.3-mbstring \
        php8.3-opcache \
        php8.3-readline \
        php8.3-xml \
        php8.3-zip \
        php8.3-mysql \
        php8.3-xdebug \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

CMD ["php", "-a"]

# If you'd like to be able to use this container on a docker-compose environment as a quiescent PHP CLI container
# you can /bin/bash into, override CMD with the following - bear in mind that this will make docker-compose stop
# slow on such a container, docker-compose kill might do if you're in a hurry
# CMD ["tail", "-f", "/dev/null"]

FROM cli AS fpm

# Install FPM
RUN apt-get update \
    && apt-get -y --no-install-recommends install php8.3-fpm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

STOPSIGNAL SIGQUIT

# PHP-FPM packages need a nudge to make them docker-friendly
COPY /etc/php-fpm/overrides.conf /etc/php/8.3/fpm/pool.d/z-overrides.conf

CMD ["/usr/sbin/php-fpm8.3", "-O" ]

# Open up fcgi port
EXPOSE 9000