# api.piratenpartei-bw.de

## Installation
### Dateistruktur
Apache braucht Schreibzugriff auf die Verzeichnisse /app/tmp und darunterliegend.
```
heidi@julia:~/workspace/api.piratenpartei-bw.de/app/tmp$ ll
insgesamt 16
drwxrwx--- 5 heidi www-data 4096 2012-03-15 20:45 cache/
drwxrwx--- 2 heidi www-data 4096 2012-03-15 21:37 logs/
drwxrwx--- 2 heidi www-data 4096 2012-03-15 20:45 sessions/
drwxrwx--- 2 heidi www-data 4096 2012-03-15 20:45 tests/
```

### Apache-vhost
```
<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        DocumentRoot /path/to/api.piratenpartei-bw.de/app/webroot
        <Directory /path/to/api.piratenpartei-bw.de/app/webroot>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
        </Directory>
        ErrorLog ${APACHE_LOG_DIR}/error.log
        LogLevel warn
        CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```
