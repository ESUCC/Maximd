#! /bin/sh
#Backup and maintenance script for IEP systems
#author: Steve Lane
#last revision: 2005-08-15

export datestamp=`date +%Y-%m-%d-%H%M%S`
export PATH=/opt/backup/scripts:${PATH}
echo "datestamp=" $datestamp

#back up postgres data
#echo "backing up postgres data"
#sh postgres_backup.sh $datestamp

#back up php files
#
# jlavere removed 12/11/2008 until new backup solution is determined
#
#echo "backing up php files"
#sh php_backup.sh $datestamp

#back up configs
#2003-07-12
#
# jlavere removed 12/11/2008 until new backup solution is determined
#
#echo "backing up configs"
#sh configs_backup.sh $datestamp

#remove old PDFs from srs tree
cd /var/www/html/srs/formproc
rm -f *.pdf

#move files to remote backup server
#
# jlavere removed 12/11/2008 until new backup solution is determined
#
#sh remote_backup.sh $datestamp


