#!/bin/bash

clear
PRG="monitor_whatsapp.php"
ps -ef | grep -v grep | grep "/$PRG"
if [ $? -ne 0 ]
then
  cd /opt/lampp/htdocs/whatsapp/
 ./monitor_whatsapp.php
fi
exit 0
