#!/bin/bash
rm -f /usr/local/zend/var/apps/https/maximd.nebraskacloud.org/443/1.0.0_320/data/cache/File_cache/*
now="$(date)"
echo "    "  | tee -a /root/maxim.log
echo  "Current date and time is " "$now" >>  /root/maxim.log
echo "------------------------------------------------------------" >>  /root/maxim.log
find $1 -type f -print0 | xargs -0 stat --format '%Y :%y %n' | sort -nr | cut -d: -f2- | head
echo "___________________________________________________________________________________________________________">>  /root/maxim.log
