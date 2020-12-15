#!/usr/bin/env bash

#== Import script args ==

github_token=$(echo "$1")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

info "Configure composer"
composer config --global github-oauth.github.com ${github_token}
echo "Done!"

# Install the composer-asset-plugin so you don't get errors about yiisoft/yii2 requires bower-asset/jquery NB: At least as of 1st Dec 2020 this only works with composer v1
composer global require "fxp/composer-asset-plugin"

info "Install project dependencies"
cd /app
composer --no-progress --prefer-dist install


info "Enabling colorized prompt for guest console"
sed -i "s/#force_color_prompt=yes/force_color_prompt=yes/" /home/vagrant/.bashrc

info "Setting up initial app credentials by running yii deploy/init"
/app/yii deploy/init --force 1

info "Creating bash-aliases for vagrant user"
cat << EOF >> /home/vagrant/.bash_aliases
## Project Vagrant VM Specific Bash Aliases
alias app="cd /app"
alias stubs="./yii stubs 'config/console.php' 'config/web.php'"

## Before deploying, especially to Staging or Production you'll want to update the latest Mozzler base
alias mozzlerUpdate='composer update mozzler/*'

## If you don't have the Mozzler base Symlinked (e.g when using Vagrant on Windows)
alias mozzlerBaseRsyncUpdate='rsync -avh --exclude=.git --exclude=vendor/ /mozzler/yii2-base /app/vendor/mozzler/'
alias mozzlerAuthRsyncUpdate='rsync -avh --exclude=.git --exclude=vendor/ /mozzler/yii2-auth /app/vendor/mozzler/'
alias mozzlerRbacRsyncUpdate='rsync -avh --exclude=.git --exclude=vendor/ /mozzler/yii2-rbac /app/vendor/mozzler/'
alias mozzlerBaseDevelopment='while true; do rsync -avh --exclude=.git --exclude=vendor/ /mozzler/yii2-base/ /app/vendor/mozzler/yii2-base/;     sleep 1; done'

## Assumes you are using AWS's EB, likely in the staging or master branch.
alias deploy='cd /app && mozzlerUpdate && git add composer.lock && git commit -m "* Latest Mozzler base"  &&  eb deploy'

## General Bash Aliases ( based on https://www.kublermdk.com/2017/01/18/my-bash_aliases-2017/ )
alias timezone_new='sudo dpkg-reconfigure tzdata'
alias acs='apt-cache search'
alias agg='sudo apt-get upgrade'
alias agi='sudo apt-get install'
alias agr='sudo apt-get remove'
alias agu='sudo apt-get update'
alias aliasd='nano ~/.bash_aliases; source ~/.bash_aliases'
alias aps='sudo dpkg --get-selections'
alias apt='sudo apt-get install'
alias a2r='sudo service apache2 reload'
alias a2rr='sudo service apache2 restart'
alias nxr='sudo service nginx reload'
alias nxrr='sudo service nginx restart'
alias bashrc='nano ~/.bashrc;  source ~/.bashrc'
alias cdetca='cd /etc/apache2/sites-available'
alias cdwww='cd /var/www/'
alias chownWWW='sudo chown -Rc www-data:www-data * .ht*'
alias da='sudo du -hsc *'
alias das='du -cks * 2>/dev/null | sort -rn | while read size fname; do for unit in k M G T P E Z Y; do if [ $size -lt 1024 ]; then echo -e "${size}${unit}\t${fname}"; break; fi; size=$((size/1024)); done; done;'
alias dir='ls --color=auto --format=vertical'
alias directoryExec='sudo find * -type d -exec chmod -c ug+x {} \;'
alias directoryRWX='find * -type d -exec chmod -c ug+rwx {} \;'
alias ds='du -hsc * | sort -n'
alias egrep='egrep --color=auto'
alias eximQ='sudo exim -bp'
alias eximRouting='exim -bt'
alias eximHeaderOfMessage='exim -Mvh '
alias eximBodyOfMessage='exim -Mvb '
alias fgrep='fgrep --color=auto'
alias grep='grep --color=auto'
alias l='ls -CF'
alias la='ls -A --color=auto'
alias ll='ls -Alsch --color=auto'
alias load='uptime'
alias logs='find /var/log/ /app/runtime/logs/ -name "*.log" -type f -exec tail -n 1 -f {} +'
alias logsErrors='find /var/log/ -name "error.log" -type f -exec tail -f {} +'
alias logsudo='sudo find /var/log/  -name "*.log" -type f -exec sudo tail -f {} +'
alias ls='ls --color=auto'
alias maillog='tail -n 50 -f /var/log/exim4/mainlog /var/log/exim4/rejectlog'
alias mailstats='eximstats /var/log/exim4/mainlog'
alias mailTestToMozzler='echo "This is a Test email from $HOSTNAME." | mail -s "Test email" "test@mozzler.com.au"'
alias mem='free -m'
alias netstats='sudo netstat -tulpn'
alias openz='gzip -d -c'
alias rm='rm -v'
alias screenDetach='echo Press [ctrl]+ a, d to detach. Screen -list to view and screen -r to reattach'
alias ssh-config='nano ~/.ssh/config'
alias ubuntuVersion='cat /etc/*-release'
alias netGraph='slurm -i eth0'
alias ifconfigNet='curl checkip.amazonaws.com'
alias ifconfigNetAll='curl ifconfig.me/all'
alias phpTime="echo -e 'Current Unixtime:\e[38;5;14m'; echo 'echo time();' | php -a 2>/dev/null | tail -1"

alias processtree='pstree -paul'
alias git_changed_files_from_commit_hash='git show --pretty="format:" --name-only '; # head is a good argument, otherwise the SHA1. Based on http://stackoverflow.com/questions/424071/list-all-the-files-for-a-commit-in-git
alias git_changed_files_from_last_commit='git show --pretty="format:" --name-status head';
alias git_time='git log --since=1.day --pretty=format:"%Cblue%ar%Cgreen %s%Creset"'
alias git_today='git log --since=1.day --pretty=format:"%Cgreen%s%Creset"'

# Show the git log entries from the last 24hrs
alias gitt='git log --since=1.day --pretty=format:"%Cblue%ar%Cgreen %s%Creset" | sed -e "s/^\([^\*]*\)//g" | sed -e "s/ \* /\n* /g"; echo ""'
# Show the git log entries from the last 24hrs and include how long ago
alias gittt='git log --since=1.day --pretty=format:"%Cblue%ar%Cgreen %s%Creset" | sed -e "s/ \* /\n* /g"; echo ""'
# Git Add and Commit (add -m "message" to set a message)
alias gac='time git add --all . && time git commit'
alias gacm='time git add --all . && time git commit -m "** A general commit update"'
alias gacpp='git pull; git add . && git commit ; git push'

alias chmod='chmod -c'
alias chown='chown -c'
alias rm='rm -v'
alias mv='mv -v'
alias cp='cp -v'

# Remove all files, including .htaccess in the folder.
alias rma='rm -rf {,.[!.],..?}*'
# A remove files version that'll work with many thousands of files
alias removeALLFilesInThisFolder='time find -mindepth 1 -maxdepth 1 -print0 | xargs -0 rm -rf'

# LS_COLORS (make directories purple instead of dark blue . The rest are what ubuntu shipped w/by default)
LS_COLORS='no=00:fi=00:di=01;35:ln=01;36:pi=40;33:so=01;35:do=01;35:bd=40;33;01:cd=40;33;01:or=40;31;01:su=37;41:sg=30;43:tw=30;42:ow=34;42:st=37;44:ex=01;32:*.tar=01;31:*.tgz=01;31:*.arj=01;31:*.taz=01;31:*.lzh=01;31:*.zip=01;31:*.z=01;31:*.Z=01;31:*.gz=01;31:*.bz2=01;31:*.deb=01;31:*.rpm=01;31:*.jar=01;31:*.jpg=01;35:*.jpeg=01;35:*.gif=01;35:*.bmp=01;35:*.pbm=01;35:*.pgm=01;35:*.ppm=01;35:*.tga=01;35:*.xbm=01;35:*.xpm=01;35:*.tif=01;35:*.tiff=01;35:*.png=01;35:*.mov=01;35:*.mpg=01;35:*.mpeg=01;35:*.avi=01;35:*.fli=01;35:*.gl=01;35:*.dl=01;35:*.xcf=01;35:*.xwd=01;35:*.flac=01;35:*.mp3=01;35:*.mpc=01;35:*.ogg=01;35:*.wav=01;35:';
export LS_COLORS


function diglookup {
 if [ -z "$1" ]; then # Then there was no argument passed to the function
     echo -n "* Please enter the domain name (e.g 'greyphoenix.biz' or 'filmsonthefly.com') : "
     read DOMAIN
     if [ "$DOMAIN" == "" ]; then # No answer to the question (User just pressed space, or timed out)
                echo "[ERROR] No domain listed. Now exiting"
                exit 2
     else
                echo "- Domain set to $DOMAIN, doing the whois and DNS lookups"
     fi # End checking for user input
 else
        DOMAIN="${1}"
 fi
echo "============================"
echo "=== ${DOMAIN} ==="
echo "============================"
echo `date`
echo "--- dig ${DOMAIN}"
dig +short ${DOMAIN}
echo ""
echo "--- dig www.${DOMAIN}"
dig +short www.${DOMAIN}
echo ""
echo "--- dig ${DOMAIN} mx"
dig +short ${DOMAIN} mx
echo ""
echo "--- dig ${DOMAIN} txt"
dig +short ${DOMAIN} txt
echo ""
echo "--- dig mail.${DOMAIN}"
dig +short mail.${DOMAIN}
echo ""
echo "--- whois ${DOMAIN}"
whois ${DOMAIN}
echo ""

DOMAIN_A_IP=`dig +short ${DOMAIN}`
echo "--- Web server's reverse IP 'nslookup ${DOMAIN_A_IP}'"
echo ""
dig +short -x ${DOMAIN_A_IP}
echo ""
nslookup ${DOMAIN_A_IP}
echo "---======---"
}

## Set the default directory
if [ -d "/app" ]; then
        cd /app
fi

EOF
