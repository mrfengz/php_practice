#!/bin/sh
cd /srv/www/test/php/swoole/;
/usr/bin/php crontab_ping.php;
date>>/tmp/ping.log;
