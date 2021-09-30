#!/bin/bash

# Predeploy scripts are run after the application is configured and container_commands have already been run

# The issue is that the .sh script files need 755 (read/execute) permissions in order to be run which Git doesn't want to do properly
# If you can ensure this script file has execution permissions then it'll make sure the others also have such permissions
# This is mainly useful for Windows developers who can't easily add +x file permissions
# NB: This script is expected to be run in the /var/app/staging/ folder

if [ -d .platform/hooks ]; then
  sudo chmod +x .platform/hooks/*/*.sh;
fi
