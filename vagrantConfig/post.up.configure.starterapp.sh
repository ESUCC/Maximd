#!/bin/sh
# /vagrant/vagrantConfig/post.up.configure.starterapp.sh

# ensure original installs of php are removed
rm -f /usr/bin/php
rm -f /etc/alternatives/php

# configure git to ignore fileMode
git config core.fileMode false

# compose the project
cd /vagrant/
/usr/local/zend/bin/php /usr/local/bin/composer self-update
COMPOSER_PROCESS_TIMEOUT=2000
/usr/local/zend/bin/php /usr/local/bin/composer install --prefer-dist --no-plugins --no-scripts --no-progress

# setup main config files -- after composing
mkdir -p /vagrant/data/cache
mkdir -p /vagrant/documentation/sequel_pro
cp /vagrant/vagrantConfig/templates/server_data.php.dist /vagrant/public/FX/server_data.php
cp /vagrant/vagrantConfig/templates/vagrant-172.20.15.109.spf.dist /vagrant/documentation/sequel_pro/vagrant-172.20.15.109.spf

mkdir /tmp/node_modules
rm -rf node_modules
ln -s /tmp/node_modules

# install postgres
sudo apt-get -y update
sudo apt-get -y install postgresql postgresql-contrib

# update conf file
rm -f /etc/postgresql/9.1/main/pg_hba.conf
cp /vagrant/vagrantConfig/templates/pg_hba.conf.dist /etc/postgresql/9.1/main/pg_hba.conf

echo "restart postgres"
sudo -u postgres /usr/lib/postgresql/9.1/bin/pg_ctl -D /var/lib/postgresql/9.1/main restart
sleep 5

# refresh bash profile
cp /vagrant/vagrantConfig/templates/bash_profile.dist /home/vagrant/.bash_profile
source ~/.bash_profile

# load database
#sudo -u postgres psql -f /vagrant/data/dumps/nebraska.pgdump
sudo -u postgres psql -f /vagrant/data/dumps/nebraska.roles.pgdump postgres
sudo -u postgres createdb -E SQL_ASCII iep_local -U postgres -T template0
sudo -u postgres psql iep_local < /vagrant/data/dumps/iep_local.pgdump

# install prince
wget http://www.princexml.com/download/prince-7.1-linux.tar.gz
tar -zxf prince-7.1-linux.tar.gz
cd prince-7.1-linux
./install.sh
cd ..
rm -Rf prince-7.1-linux
rm -Rf prince-7.1-linux.tar.gz

# npm install
# echo "...initial npm install complete.\nRunning additional post install configuration.\n"

# bower - update packages
# this tells bower to download packages defined in bower.json
# echo "code:node_modules/.bin/bower install:\n"
# node_modules/.bin/bower install --allow-root

# grunt - run  all tasks - build requirejs config
# echo "code:grunt:\n"
# grunt

# build the minified modularized js files
#echo "build javascript (configured in app.config.js):\n"
#echo "code:node node_modules/.bin/r.js -o /vagrant/build-scripts/require.build.config.js\n"
#node node_modules/.bin/r.js -o /vagrant/build-scripts/require.build.config.js
echo "finished with app install\n"
