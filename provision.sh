#!/usr/bin/env bash

# Mise à jour des dépots
apt-get -qq update

# Configuration de la timezone
echo "Europe/Paris" > /etc/timezone
apt-get install -y tzdata
dpkg-reconfigure -f noninteractive tzdata

# Installation de Apache et PHP
apt-get -y install libapache2-mod-php5 php5-cli

# Installation de Git
apt-get -y install git

# Configuration du projet
apt-get install -y ant
cd /vagrant
git submodule update --init --recursive
./composer.phar install --prefer-dist --no-progress
ant configure build -Dprofile=vagrant

# Mise à disposition du projet dans Apache
ln -sf /vagrant/src/public/* /var/www/html/
rm -f /var/www/html/index.html

# Informations
echo
echo -e "Le site est disponible à l'adresse : http://mirror-pastis.vagrant.dev/frontend_dev.php"
