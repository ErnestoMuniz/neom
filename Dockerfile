FROM php:8.2-apache

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    python3 \
    python3-pip \
    snmpd \
    snmp \
    libsnmp-dev

# Enable mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip pdo_pgsql pgsql; exit 0

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set the working directory
WORKDIR /var/www/html

# Copy the application code
COPY . /var/www/html

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get install -y \
    python3 \
    python3-pip
    
RUN pip install --break-system-packages paramiko
RUN pip install --break-system-packages easysnmp
RUN ln -s /usr/bin/python3 /usr/bin/python

# Install project dependencies
RUN composer install

# Set permissions
RUN chown -R www-data:www-data /var/www/html/