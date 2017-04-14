#!/bin/sh
# configured to run on iepweb03

/usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/movePPNFilesToFolder.php -e iepweb03 -n 004 -b 1/1/2015
/etc/httpd/srs-zf/scripts/cron/PPN/putpdfs.exp
