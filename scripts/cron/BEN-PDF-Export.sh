#!/bin/sh
# configured to run on iepweb03

# Mike changed this 12-14-2017 from the command below SRS-148 on cron pdf creation. Lines 8 and 9 commented out from iepweb03
/usr/local/zend/bin/php -d include_path='/usr/local/zend/var/libraries/Zend_Framework_1/1.12.16/library/:/usr/loca/zend/share/pear/' -d extension_dir='/usr/local/zend/lib/php_extensions/' -d enable_dl=On -d extension=pgsql.so -d extension=pdo_pgsql.so /usr/local/zend/var/apps/https/iepweb02dev.nebraskacloud.org/80/1.0.0_84/scripts/cron/moveBENFilesToFolder.php -e iepweb03  -n 004 -b 1/1/2016


# /usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/moveGRTFilesToFolder.php -e iepweb03 -n 004 -b 1/1/2015
# /etc/httpd/srs-zf/scripts/cron/GRT/putGRTpdfs.exp
/usr/local/zend/var/apps/https/iepweb02dev.nebraskacloud.org/80/1.0.0_84/scripts/cron/BEN/BEN.sh

