#!/bin/bash

/usr/bin/supervisord &
/usr/local/sbin/php-fpm
