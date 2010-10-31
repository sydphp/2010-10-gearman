#!/bin/bash
/usr/local/sbin/gearmand -d -u root -b 32 -p 4730 -t 0 -P /var/run/gearmand.pid -vvvv -l /var/log/gearmand.log
