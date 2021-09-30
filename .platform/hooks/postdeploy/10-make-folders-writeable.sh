#!/bin/bash

# Make Folders Writable
# Similar to https://github.com/rennokki/laravel-aws-eb/blob/master/.platform/hooks/postdeploy/x_make_folders_writable.sh

# After the deployment finished, give the full 0777 permissions
# to the runtime folder which contains the logs, cache etc..
sudo chmod -R 777 runtime/
