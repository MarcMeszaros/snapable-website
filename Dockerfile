FROM        ubuntu:trusty
MAINTAINER  Marc Meszaros <marc@snapable.com>

# install dependencies
RUN apt-get update && apt-get -y install \
    ntp \
    curl \
    apache2 \
    libapache2-mod-php5 \
    php5 \
    php5-cli \
    php5-curl \
    php5-gd

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# ssl
ADD ssl /ssl

# configure apache
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_PID_FILE /var/run/apache2.pid
ENV APACHE_RUN_DIR /var/run/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_LOG_DIR /var/log/apache2

RUN a2enmod rewrite ssl
RUN sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 10M/' /etc/php5/apache2/php.ini

RUN rm /etc/apache2/sites-enabled/*
COPY script/000-snapable.conf /etc/apache2/sites-available/000-snapable.conf
RUN ln -s /etc/apache2/sites-available/000-snapable.conf /etc/apache2/sites-enabled/000-snapable.conf

# install composer deps
ADD composer.json /tmp/composer.json
RUN cd /tmp && composer install

# app code setup
ADD system /src/system/
ADD vendor /src/vendor/
ADD application /src/application/
ADD public_html /src/public_html/

# running
EXPOSE 80 443
ENTRYPOINT ["/usr/sbin/apache2"]
CMD ["-D", "FOREGROUND"]