#!/bin/sh
HOST='ednax.unl.edu'
USER='linux'
PASSWD='bkup'
FILE_PREFIX=${1}
BACKUP_DIR='/opt/backup/nightly_remote'
BACKUP_ROOT='/opt/backup'
BACKUP_SCRIPTS='/opt/backup/scripts'
CURL=/usr/local/bin/curl
SITE=ftp://ednax.unl.edu/iep/
FTPID=linux:bkup

rm -f $BACKUP_DIR/*gz
ln /opt/backup/php/${FILE_PREFIX}*gz $BACKUP_DIR
#ln /opt/backup/postgres/${FILE_PREFIX}*gz $BACKUP_DIR
ln /opt/backup/config/${FILE_PREFIX}*gz $BACKUP_DIR

cd $BACKUP_ROOT
tar czf $BACKUP_DIR/scripts.tgz scripts

cd $BACKUP_DIR 
#scp -q *pgdata.*gz iep@secd.unl.edu:./backups
scp -q *phpfiles.tgz iep@secd.unl.edu:./backups
scp -q *config.tgz iep@secd.unl.edu:./backups
scp -q scripts.tgz iep@secd.unl.edu:./backups
#$CURL -s $SITE -u $FTPID -T *pgdata.*gz
#$CURL -s $SITE -u $FTPID -T *phpfiles.tgz
#$CURL -s $SITE -u $FTPID -T *config.tgz
#$CURL -s $SITE -u $FTPID -T scripts.tgz
