# api.piratenpartei-bw.de

## Apache-vhost

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

