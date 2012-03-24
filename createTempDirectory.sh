#!/bin/bash
mkdir app/tmp
mkdir app/tmp/cache
mkdir app/tmp/cache/models
mkdir app/tmp/cache/persistent
mkdir app/tmp/cache/views
mkdir app/tmp/logs
mkdir app/tmp/sessions

echo "Bitte nun für app/tmp rekursiv Schreibrechte für den Webserver verleihen. Beispiel:"
echo "sudo chmod -R 775 app/tmp"
echo "sudo chgrp -R www-data app/tmp"
