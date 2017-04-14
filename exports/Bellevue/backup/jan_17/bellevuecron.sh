#!/bin/bash
/usr/local/zend/bin/php /usr/local/zend/var/apps/https/iepweb02.esucc.org/80/1.0.0_84/exports/Bellevue/run_export.php production
sed -i -e 's/<strong>//g' /usr/local/zend/var/apps/https/iepweb02.esucc.org/80/1.0.0_84/exports/Bellevue/SRStoBellevue.txt
sed -i -e 's/<\/strong>//g' /usr/local/zend/var/apps/https/iepweb02.esucc.org/80/1.0.0_84/exports/Bellevue/SRStoBellevue.txt 
/usr/local/zend/var/apps/https/iepweb02.esucc.org/80/1.0.0_84/exports/Bellevue/bellftp.exp
