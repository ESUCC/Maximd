#!/bin/bash

/usr/local/zend/bin/php /usr/local/zend/var/apps/https/iepweb02.nebraskacloud.org/80/1.0.0_84/exports/Bellevue/run_export.php production
sed -i -e 's/<strong>//g' /usr/local/zend/var/apps/https/iepweb02.nebraskacloud.org/80/1.0.0_84/exports/Bellevue/SRStoBellevue.txt
sed -i -e 's/<\/strong>//g' /usr/local/zend/var/apps/https/iepweb02.nebraskacloud.org/80/1.0.0_84/exports/Bellevue/SRStoBellevue.txt
/usr/local/zend/var/apps/https/iepweb02.nebraskacloud.org/80/1.0.0_84/exports/Bellevue/bellftp.exp


# /usr/local/zend/bin/php /usr/local/zend/var/apps/https/iepweb02.nebraskacloud.org/443/1.0.0_268/exports/Bellevue/run_export.php production
# sed -i -e 's/<strong>//g' /usr/local/zend/var/apps/https/iepweb02.nebraskacloud.org/443/1.0.0_268/exports/Bellevue/SRStoBellevue.txt
# sed -i -e 's/<\/strong>//g' /usr/local/zend/var/apps/https/iepweb02.nebraskacloud.org/80/1.0.0_84/exports/Bellevue/SRStoBellevue.txt
# /usr/local/zend/var/apps/https/iepweb02.nebraskacloud.org/80/1.0.0_84/exports/Bellevue/bellftp.exp
