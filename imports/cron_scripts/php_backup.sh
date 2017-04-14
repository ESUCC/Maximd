#! /bin/sh
#Backup script for php and postgres
#author: Steve Lane
#last revision: 2002-08-29
#accepts the backup datestamp as a parameter

#back up php files
export filename=${1}_phpfiles.tar
echo "exporting php data to file $filename"
cp -R /var/www/html/srs /opt/backup/php
cd /opt/backup/php/srs
    rm -f error_log* 
    rm -f pageviews*
    rm -rf phpPgAdmin
    rm -rf formproc
    rm -rf images
    rm -rf lps/uploads
    rm -rf resources
    rm -rf tutorials
cd /opt/backup
tar cf php/$filename php/srs
/bin/gzip -9 php/$filename
rm -rf php/srs
