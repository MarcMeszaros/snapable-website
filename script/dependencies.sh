#!/usr/bin/env bash

# install packages
echo ""
echo "+-------------------------+"
echo "| Install System Packages |"
echo "+-------------------------+"
echo ""
apt-get update
apt-get -y install ntp git curl apache2-mpm-prefork libapache2-mod-php5 php5 php5-cli php5-curl php5-gd

echo ""
echo "+------------------+"
echo "| Install Composer |"
echo "+------------------+"
echo ""
cd /usr/local/bin
curl -sS https://getcomposer.org/installer | php
chmod a+x composer.phar
