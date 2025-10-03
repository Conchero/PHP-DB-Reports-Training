#!/bin/bash
# POSTGRES_USER=postgres
# POSTGRES_HOST=db
# POSTGRES_NAME=training
# POSTGRES_PASSWORD=dbtraining
# POSTGRES_PORT=5432



* * * * * /usr/local/bin/php /var/www/html/backend/reports/ReportManager.php > /var/log/cronjobLogs/cronLog.log 
