<?php
/**
 * ARCHIVING FILES TO PDF ON THE NEBRASKA SRS SYSTEM
 */

/**
 * be sure that the Solr server is running on 72.15.175.206
 */
ssh root@72.15.175.206
cd /export/solr/3.6.1/example
java -jar start.jar &

/**
 * log onto iepweb02 or iepweb03
 */


cd /etc/httpd/srs-zf



/**
 * navigate to the cron folder
 */
cd /etc/httpd/srs-zf/scripts/cron


/**
 * for archiving to work properly, the table structure in the archive db must match the structure in the main db
 * the following commands will show you the difference between the two dbs. Use the output from diff to add needed structure to archive db
 * these should be run on iepweb02 or iepweb03
 */
// diff
/usr/local/zend/bin/php ./library/Pgdbsync/pgdbsync -s public -f primary -t archive -a diff -c /etc/httpd/srs-zf/application/configs/pgsync-conf.ini
// summary
/usr/local/zend/bin/php ./library/Pgdbsync/pgdbsync -s public -f primary -t archive -a summary -c /etc/httpd/srs-zf/application/configs/pgsync-conf.ini
// run
/usr/local/zend/bin/php ./library/Pgdbsync/pgdbsync -s public -f primary -t archive -a run -c /etc/httpd/srs-zf/application/configs/pgsync-conf.ini

// USE SCREEN
// after logging into iepweb03, type 'screen'. Then start the archive and use ctl+a+d to exit and screen -r to resume

// OFFICIAL START OF ARCHIVING
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 001 -b 1/1/2000 -d 1

/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/restoreArchiveToDb.php -e iepweb03 -n 001 -i 1086132





/**
 * Call the archiving script
 * param 1 (platform): iepweb02 or iepweb03
 * param 2 (form number with leading zeros): 001, 024, etc
 * param 3 the cutoff date (process forms before this date)
 */
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 004 -b 1/1/2001


/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 001 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 002 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 003 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 004 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 005 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 006 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 007 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 008 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 009 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 011 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 012 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 013 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 014 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 015 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 016 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 017 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 018 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 019 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 020 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 021 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 022 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 023 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 024 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 025 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 026 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 027 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 028 -b 1/1/1991 -d 1
/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 029 -b 1/1/1991 -d 1

// latest testing
// /usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/archive.php -e iepweb03 -n 004 -b 1/1/2015