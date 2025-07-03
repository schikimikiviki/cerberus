#!/bin/bash

APP_DEST="/var/lib/docker/volumes/cerberus_cerberus_nextcloud/_data/custom_apps/"
CERBERUS_DEST="/var/lib/docker/volumes/cerberus_cerberus_nextcloud/_data/custom_apps/cerberus"

cp -r cerberus "$APP_DEST"
chown -R www-data:www-data "$CERBERUS_DEST"
chmod -R 755 "$CERBERUS_DEST"
echo "copied to dest!"



