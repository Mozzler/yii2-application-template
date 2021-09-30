#!/bin/bash

# Sets the htop config which has tree view enabled by default and basename highlighting which is nicer
if [ -f .platform/files/htoprc ]; then
  sudo mkdir -p /root/.config/htop/htoprc
  sudo mkdir -p /home/ec2-user/.config/htop/

  sudo cp .platform/files/htoprc /root/.config/htop/htoprc
  sudo cp .platform/files/htoprc /home/ec2-user/.config/htop/htoprc

  sudo chown root:root -Rc /root/.config
  sudo chown ec2-user:ec2-user -Rc /home/ec2-user/.config
fi