#!/bin/bash

# Prebuild scripts are run after the application has been extracted but before it's been configured

# The issue is that the .sh script files need 755 (read/execute) permissions in order to be run which Git doesn't want to do properly
# If you can ensure this script file has execution permissions then it'll make sure the others also have such permissions
# This is mainly useful for Windows developers who can't easily add +x file permissions
# NB: This script is expected to be run in the /var/app/staging/ folder

# This file needs chmod +x applied to it. If you have issues try running this from the base application folder
# > git update-index --chmod=+x .platform/hooks/*/*.sh

if [ -d .platform/hooks ]; then
  sudo chmod +x .platform/hooks/*/*.sh;
fi

echo "OK"