#! /bin/sh
#Backup script for various configs and utilities
#author: Steve Lane
#last revision: 2005-05-26
#accepts the backup datestamp as a parameter

#back up apache confi
export filename=${1}_config.tgz

cp -R /usr/apachesecure0/conf /opt/backup/config
cp -R /opt/backup/scripts /opt/backup/config
cd /opt/backup/config
tar czf $filename conf scripts
rm -rf conf
rm -rf scripts






