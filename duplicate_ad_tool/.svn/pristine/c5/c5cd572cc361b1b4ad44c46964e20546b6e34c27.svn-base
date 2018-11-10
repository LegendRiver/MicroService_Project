#!/bin/sh
# 需先赋予755权限
    ps -ef | grep duplicate_run.php | grep -v "grep" | awk '{ print $2 }' | xargs kill -9