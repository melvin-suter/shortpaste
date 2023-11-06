#!/bin/bash

SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

sass $SCRIPT_DIR/src/styles/styles.scss $SCRIPT_DIR/src/public/assets/styles.css
