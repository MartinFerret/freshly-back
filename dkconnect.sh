#!/usr/bin/env bash

source docker.env

echo "Web:             http://localhost:$APP_PORT"
echo "phpMyAdmin:      http://localhost:$PMA_PORT"

docker exec -it "$APP_NAME" bash
