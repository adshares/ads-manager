#!/usr/bin/env bash

set -e

# Ubuntu 18.04 only

# Install dependencies for composer operations
apt-get -qq -y --no-install-recommends install \
    php7.2-cli php7.2-curl php7.2-zip php7.2-xdebug php7.2-gd unzip php-pear php7.2-dev make

# Get composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

# Install composer
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"

# Install composer plugin for faster operations
composer global require hirak/prestissimo
