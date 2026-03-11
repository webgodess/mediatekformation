#!/bin/sh

DATE=$(date -I)

find /savebdd/bddbackup_* -mtime -1 -exec rm {} \;

mysqldump -h "$MYSQLHOST" -P "$MYSQLPORT" -u "$MYSQLUSER" -p"$MYSQLPASSWORD" --databases "$MYSQLDATABASE" --single-transaction | gzip > /savebdd/bddbackup_${DATE}.sql.gz