#!/bin/bash

# Ensure we are using a known working version of Composer.
# Note that Composer v2 doesn't support the composer-asset-plugin which we need for the older version of Bootstrap being used
export COMPOSER_HOME=/root
sudo /usr/bin/composer.phar self-update 1.7.2
sudo /usr/bin/composer.phar global require "fxp/composer-asset-plugin:~1.2"
