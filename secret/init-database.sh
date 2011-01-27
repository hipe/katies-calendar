#!/bin/sh

TGT="/var/sites/fapping/dicktarded-databases/katies-calendar-comments.sqlite3"

sqlite3 "$TGT" < schema.sqlite3

echo "maybe wrote $TGT"