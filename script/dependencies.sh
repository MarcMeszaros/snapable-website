#!/usr/bin/env bash

# install packages
echo ""
echo "+-------------------------+"
echo "| Install System Packages |"
echo "+-------------------------+"
echo ""
apt-get update
apt-get -y install ntp git apache2-mpm-prefork libapache2-mod-php5 php5-curl php5-gd

