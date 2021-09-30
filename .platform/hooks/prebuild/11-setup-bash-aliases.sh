#!/bin/bash

# Ensures the Bash Aliases are used for the main ec2-user
# This makes SSHing into the machines easier

grep '~/.bash_aliases' '/home/ec2-user/.bashrc'
if [ $? -gt 0 ]; then
    echo 'if [ -f ~/.bash_aliases ]; then  . ~/.bash_aliases; fi' >> /home/ec2-user/.bashrc
fi