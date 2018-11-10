#!/bin/sh
# 需先赋予755权限

    PHP_EXEC=$(which php);
    CURDIR=$(cd $(dirname $0); pwd );
    echo ${PHP_EXEC};
    echo ${CURDIR};
    COMBINE='/duplicate_run.php';
    nohup $(eval ${PHP_EXEC} ${CURDIR}${COMBINE})   > /dev/null &
    disown
    exit
