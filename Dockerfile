FROM php:zts-bullseye
ENV COMPOSER_ALLOW_SUPERUSER 1
# Copy in files
COPY . /user/src/otto
WORKDIR /user/src/otto

# Install Mysql
RUN apt-get update && apt-get install -y default-mysql-client make p7zip zip unzip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install PHP packages
RUN composer install --ignore-platform-reqs

EXPOSE 8080

CMD php -S 0.0.0.0:8080 -t public
