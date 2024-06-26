#!/bin/bash

SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

cd $SCRIPT_DIR/src/public

export SITE_TITLE="Dev Environment"
export PASTE_EXPIRATION=15
export URL_PRETTY="false"

sass --watch $SCRIPT_DIR/src/styles/styles.scss $SCRIPT_DIR/src/public/assets/styles.css & \
php -S localhost:8080  && fg