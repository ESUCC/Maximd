#!/bin/sh

# check that required private_network arg has been passed to the script
if [ -z $1 ]; then
    echo "private_network arg must be passed to this shell script as the first argument"
    echo "Example: \"192.168.15.100\""
    echo "Example: \"my.app.local\""
    exit 1
fi
private_network=$1
echo "private_network: $private_network"

# check if optional subdomains have been passed to the script
if [ -z $2 ]; then
    dev_subdomain="dev.$private_network"
else
    dev_subdomain="$2"
fi
echo "dev_subdomain: $dev_subdomain"
if [ -z $3 ]; then
    phpmyadmin_subdomain="phpmyadmin.$private_network"
else
    phpmyadmin_subdomain="$3"
fi
echo "phpmyadmin_subdomain: $phpmyadmin_subdomain"
auth_subdomain="auth.$private_network"
echo "auth_subdomain: $auth_subdomain"

# get ZS variables from file
zend_api_key_name=$(grep "zend_api_key_name" /etc/facter/facts.d/zend_api_key_name.txt | cut -d'=' -f2)
zend_api_key_hash=$(grep "zend_api_key_hash" /etc/facter/facts.d/zend_api_key_hash.txt | cut -d'=' -f2)
echo "zend_api_key_name: $zend_api_key_name"
echo "zend_api_key_hash: $zend_api_key_hash"
sleep 2

# install non-default Zend Server PHP extensions
/usr/local/zend/bin/zs-manage extension-on -e imagick -N $zend_api_key_name -K $zend_api_key_hash
sleep 2

# set Zend Server PHP directives to development preferences
## default is 'E_ALL & ~E_DEPRECATED & ~E_STRICT'
/usr/local/zend/bin/zs-manage store-directive -d 'error_reporting' -v 'E_ALL' -N $zend_api_key_name -K $zend_api_key_hash
sleep 2

## default is 'Off'
/usr/local/zend/bin/zs-manage store-directive -d 'display_errors' -v 'On' -N $zend_api_key_name -K $zend_api_key_hash
sleep 2

## default is '128M'
/usr/local/zend/bin/zs-manage store-directive -d 'memory_limit' -v '512M' -N $zend_api_key_name -K $zend_api_key_hash
sleep 2

## default is '30'
/usr/local/zend/bin/zs-manage store-directive -d 'max_execution_time' -v '60' -N $zend_api_key_name -K $zend_api_key_hash
sleep 2

###disabled until we find fix for login clash
### default is disabled
#/usr/local/zend/bin/zs-manage store-directive -d 'zray.enable' -v '1' -N $zend_api_key_name -K $zend_api_key_hash
#sleep 2

## default is null
/usr/local/zend/bin/zs-manage store-directive -d 'mail.log' -v '/vagrant/data/mail.log' -N $zend_api_key_name -K $zend_api_key_hash
sleep 2

# install vhosts
# starter app vhost
/usr/local/zend/bin/zs-manage vhost-add -n $dev_subdomain -p 80 \
    -t "`cat /vagrant/vagrantConfig/templates/vhost.soliant.starter`" -N $zend_api_key_name -K $zend_api_key_hash
# phpmyadmin vhost
/usr/local/zend/bin/zs-manage vhost-add -n $phpmyadmin_subdomain -p 80 \
    -t "`cat /vagrant/vagrantConfig/templates/vhost.phpmyadmin.soliant`" -N $zend_api_key_name -K $zend_api_key_hash

# restart must happen before deploys because they may require the installed extensions or vhosts
/usr/local/zend/bin/zs-manage restart -N $zend_api_key_name -K $zend_api_key_hash

# deploy phpmyadmin
#cd /tmp
#/usr/bin/curl -o phpmyadmin.zpk -L http://updates.zend.com/zpkai/index
#/usr/local/zend/bin/zs-manage app-deploy \
#  -p /tmp/phpmyadmin.zpk \
#  -b "http://$phpmyadmin_subdomain/" \
#  -a phpMyAdmin \
#  -u "my_passwd=strongpassword" \
#  -N $(grep "zend_api_key_name" /etc/facter/facts.d/zend_api_key_name.txt | cut -d'=' -f2) \
#  -K $(grep "zend_api_key_hash" /etc/facter/facts.d/zend_api_key_hash.txt | cut -d'=' -f2)

