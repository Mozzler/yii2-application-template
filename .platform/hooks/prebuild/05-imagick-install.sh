#!/bin/bash
# Install the imagick extension for PHP as it's not available as a YUM package on PHP 7.4 RHEL (AMI Linux v2), they said something about tests not passing
# If you have issues with then try this version https://github.com/rennokki/laravel-aws-eb/blob/master/.platform/hooks/prebuild/install_imagick.sh

# Optional - This is for the PHP imagick extension, disable this and the yum entries in .ebextensions/05-environment.config if you don't need it
php -m | grep imagick > /dev/null
if [[ $? -gt 0 ]]; then
  echo "Installing imagick"
  yes '' | sudo pecl install -f imagick
#  echo 'extension=imagick.so' > /etc/php.d/60-imagick.ini # Shouldn't be needed as PECL adds this
fi
