#!/bin/bash

USER=$(grep '\'user\' /etc/centreon/centreon.conf.php | awk -F= '{ print $2 }' | sed -e 's/[;" ]//g')
PASSWORD=$(grep '\'password\' /etc/centreon/centreon.conf.php | awk -F= '{ print $2 }' | sed -e "s/[;' ]//g")
DB_CENTREON=$(grep '\'db\' /etc/centreon/centreon.conf.php | awk -F= '{ print $2 }' | sed -e 's/[;" ]//g')

FILE_PATH="/etc/centreon-broker/"
HASH_PATH="/etc/centreon/"
configNameFiles[0]='centreon-poller-display'
configNameFiles[1]='bam-poller-display'
extend_save='-save.txt'
extend_sql='.sql'

for ((i=0; i < ${#configNameFiles}; i++))
do

SQL_FILE=$FILE_PATH${configNameFiles[$i]}$extend_sql
HASH_FILE=$HASH_PATH${configNameFiles[$i]}$extend_save

    if [ -f $HASH_FILE ] ; then
        hash_content=`cat $HASH_FILE`
    else
        hash_content=''
    fi

filecontent=`cat $SQL_FILE`
hashFile=`md5sum < $HASH_FILE`

    if [ $hash_content != $hashFile ] ; then
        mysql --user="$USER" --password="$PASSWORD" --database="$DB_CENTREON" --execute="$filecontent"
        echo $hashFile > $HASH_FILE
        service cbd restart
    fi
done
