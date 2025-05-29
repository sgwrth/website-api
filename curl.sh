#!/bin/bash

METHOD=$1
ENDPOINT=$2
EMAIL=$3
PASSWORD=$4
USERNAME=$5

HOST=127.0.0.1
PORT="8000"

JSON_DATA=$(printf '{"email": "%s", "password": "%s"}' "$EMAIL" "$PASSWORD")
JSON_DATA_WITH_USERNAME=$(printf '{"email": "%s", "password": "%s", "username": "%s"}' "$EMAIL" "$PASSWORD" "$USERNAME")

if [[ "$ENDPOINT" == "login" ]]; then
    curl -X "${METHOD^^}" http://"$HOST":"$PORT"/api/"$ENDPOINT" \
        -H "Content-Type: application/json" \
        -d "$JSON_DATA" \
        | json_pp
fi
if [[ "$ENDPOINT" == "register" ]]; then
    curl -X "${METHOD^^}" http://"$HOST":"$PORT"/api/"$ENDPOINT" \
        -H "Content-Type: application/json" \
        -d "$JSON_DATA_WITH_USERNAME" \
        | json_pp
fi
