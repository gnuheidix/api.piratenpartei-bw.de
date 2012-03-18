# api.piratenpartei-bw.de

## Installation
### Dateistruktur
Apache braucht Schreibzugriff auf die Verzeichnisse /app/tmp und darunterliegend
 cache/ logs/ sessions/ tests/

### Datenbank
Das folgende Schema wird momentan gebraucht.
```
CREATE TABLE wiki_pages ( 
    id int(10) unsigned NOT NULL auto_increment,
    title varchar(255) NOT NULL,
    content text NOT NULL,
    updated datetime default null,
    created datetime default null,
    PRIMARY KEY (id)
 );
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
