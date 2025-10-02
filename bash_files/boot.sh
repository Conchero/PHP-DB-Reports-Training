#!/bin/bash


if [ -d "/var/reports/" ]; then
echo "hello"
else
mkdir /var/reports/

php /var/www/html/backend/reports/ReportManager.php GlobalReport
fi