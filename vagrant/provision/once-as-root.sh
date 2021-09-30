#!/usr/bin/env bash

#== Import script args ==

timezone=$(echo "$1")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Configure MongoDB 4.4 Repos"
# As per https://www.digitalocean.com/community/tutorials/how-to-install-mongodb-on-ubuntu-20-04
curl -fsSL https://www.mongodb.org/static/pgp/server-4.4.asc | sudo apt-key add -
echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/ubuntu focal/mongodb-org/4.4 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-4.4.list


info "Configure PHP7.4 repos"
add-apt-repository ppa:ondrej/php

info "Update OS software"
apt-get update
apt-get upgrade -y

info "Install additional software"
apt-get install -y php7.4-curl php7.4-cli php7.4-intl php7.4-gd php7.4-fpm php7.4-mbstring php7.4-xml php7.4-dev php7.4-mongodb unzip nginx php.xdebug mongodb-org htop net-tools

#info "Install PHP MongoDB"
# NB: Already installed now with php7.4-mongodb
#wget http://pear.php.net/go-pear.phar
#echo "y" | php go-pear.phar
#pecl install mongodb
#echo "extension=mongodb.so" >> /etc/php/7.4/fpm/conf.d/30-mongo.ini
#echo "extension=mongodb.so" >> /etc/php/7.4/cli/conf.d/30-mongo.ini

info "Configure MongoDB"
cp /etc/mongod.conf /etc/mongodb.conf.original
cp -f /app/vagrant/mongodb/mongodb.conf /etc/mongodb.conf
# Ensure it's enabled
systemctl unmask mongodb
service mongodb restart
# Ensure it'll boot on startup
systemctl enable mongodb


info "Configure PHP-FPM"
sed -i 's/user = www-data/user = vagrant/g' /etc/php/7.4/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/7.4/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/7.4/fpm/pool.d/www.conf
cat << EOF > /etc/php/7.4/mods-available/xdebug.ini
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_connect_back=1
xdebug.remote_port=9000
xdebug.remote_autostart=1
EOF
echo "Done!"

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Enabling site configuration"
ln -s /app/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --1 --install-dir=/usr/local/bin --filename=composer

info "Add more Swap Space for Composer"
# As per https://getcomposer.org/doc/articles/troubleshooting.md#proc-open-fork-failed-errors and https://www.digitalocean.com/community/tutorials/how-to-add-swap-on-ubuntu-14-04
fallocate -l 2G /var/swap.1
/sbin/mkswap /var/swap.1
/bin/chmod 0600 /var/swap.1
/sbin/swapon /var/swap.1
echo '/var/swap.1 none swap sw 0 0' | sudo tee -a /etc/fstab
# Reduce the system's use of swap from the default 60%
echo 'vm.swappiness=30' | sudo tee -a /etc/sysctl.conf
echo 'vm.vfs_cache_pressure = 50' | sudo tee -a /etc/sysctl.conf