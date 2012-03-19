# api.piratenpartei-bw.de

## Installation
### Dateistruktur
Apache braucht Schreibzugriff auf die Verzeichnisse /app/tmp und darunterliegend
 cache/ logs/ sessions/ tests/

### Datenbank
Die Dateien /app/Config/database_dev.php bzw. database_prod.php mit korrekten Werten
befüllen.

Im DBMS die spezifizierte Datenbank anlegen. Beispiel:
```
CREATE DATABASE api_piratenpartei_bw_de;
```

Das benötigte Datenbankschema ist mit folgenden Cake-Konsolenbefehlen zu
laden.

```
a@b:~/api.piratenpartei-bw.de/app$ ./Console/cake schema create WikiPages -v
```

```
a@b:~/api.piratenpartei-bw.de/app$ ./Console/cake schema create WikiDivs -v
```

### Apache-vhost
Ein Apache-Webserver ist nach dem Vorbild der folgenden Beispielkonfiguration
zu verarzten.

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
