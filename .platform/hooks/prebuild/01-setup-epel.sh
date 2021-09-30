#!/bin/bash
# The extra yum packages

yum search epel-release
if [ $? -gt 0 ]; then
  sudo amazon-linux-extras enable epel
  sudo yum install -y epel-release

fi

yum search ncdu
if [ $? -gt 0 ]; then
  # Install ncdu for finding large files/folders
  sudo yum install -y ncdu
fi

echo "Done" # This is so that even if the yum install returns a non-zero exit code the rest still continues as this is just a nice to have, as far as I'm aware it's not (yet) required
