#!/bin/bash
# Install the MongoDB extension for PHP as it's not installed on PHP 7.4 RHEL (AMI Linux v2) by default

# -- Check if MongoDB Repo configured
# NOTE: This is the same as what's configured in .ebextensions/01-mongodb.config and that should work, enable it if that doesn't seem to work

#if [ ! -f /etc/yum.repos.d/mongodb-org-5.0.repo ]; then
#  sudo touch /etc/yum.repos.d/mongodb-org-5.0.repo
#  sudo chmod 777 /etc/yum.repos.d/mongodb-org-5.0.repo
#  cat > /etc/yum.repos.d/mongodb-org-5.0.repo <<End-of-message
#[mongodb-org-5.0]
#name=MongoDB Repository
#baseurl=https://repo.mongodb.org/yum/amazon/2/mongodb-org/5.0/x86_64/
#gpgcheck=1
#enabled=1
#gpgkey=https://www.mongodb.org/static/pgp/server-5.0.asc
#End-of-message
#  sudo chmod 644 /etc/yum.repos.d/mongodb-org-5.0.repo
#
#fi

# -- Check if MongoDB installed
yum list installed mongodb-org-tools
if [ $? -gt 0 ]; then

  # -- Hopefully Yum picks up the repo entry, if not you can try clearing the cache
  # yum search mongodb > /tmp/mongodb-install-yum-search.sh
  # sudo rm -fr /var/cache/yum/*
  # sudo yum clean all
  sudo yum install -y mongodb-org-shell mongodb-org-tools
fi

# Install the PHP-mongodb extension
if [ ! -f /usr/lib64/php/modules/mongodb.so ]; then
  sudo pecl install mongodb
fi
