#!/usr/bin/env bash

# setup apache (first run only)
if [ ! -f ~/vagrant_bootstrap ]; then
    echo ""
    echo "+---------------+"
    echo "| Setup Apache2 |"
    echo "+---------------+"
    echo ""
    a2enmod rewrite
    # add custom tweaks to the php config file
    sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 10M/' /etc/php5/apache2/php.ini
    service apache2 restart
fi

# setup the snapable api code
echo ""
echo "+----------------+"
echo "| Setup Snapable |"
echo "+----------------+"
echo ""
if [ ! -f /etc/apache2/sites-available/000-snapable.conf ]; then
    cp -f /vagrant/script/000-snapable.conf /etc/apache2/sites-available/
    a2ensite 000-snapable.conf
    a2dissite default
    service apache2 reload
fi
cd /vagrant
composer.phar install

# touch a file to know that the setup is done
touch ~/vagrant_bootstrap