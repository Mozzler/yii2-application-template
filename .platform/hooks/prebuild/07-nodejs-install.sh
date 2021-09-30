#!/bin/bash
# Defaults to Node v6.17.1 if you don't add this curl (set it to node v12.22.6 in Sept 2021)
# This script was updated based on https://github.com/rennokki/laravel-aws-eb/blob/master/.platform/hooks/prebuild/install_latest_node_js.sh


# You likely don't need NodeJS and so don't need to enable this, but it's here if you need it


#node -v | grep -E "v[0-9]{2}\."
#if [ $? -gt 0 ]; then
#  # Using a version of node lower than v10 e.g v6.xx which is what's used by default
#  sudo yum remove -y nodejs npm
#fi
#
#
#yum list installed nodejs
#if [ $? -gt 0 ]; then
#  sudo rm -fr /var/cache/yum/*
#  sudo yum clean all
#  curl --silent --location https://rpm.nodesource.com/setup_12.x | sudo bash -
#  sudo yum install nodejs -y
#fi
