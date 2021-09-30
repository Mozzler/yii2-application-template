#!/bin/bash
# Installs 2GB of Swap space so we can run things like Composer install or the NodeJS script without issues
if [ ! -f /var/swap.1 ]; then
    sudo fallocate -l 2G /var/swap.1
    sudo /sbin/mkswap /var/swap.1
    sudo /bin/chmod 0600 /var/swap.1
    sudo /sbin/swapon /var/swap.1
    echo '/var/swap.1 none swap sw 0 0' | sudo tee -a /etc/fstab
    echo 'vm.swappiness=30' | sudo tee -a /etc/sysctl.conf
    echo 'vm.vfs_cache_pressure = 50' | sudo tee -a /etc/sysctl.conf
fi