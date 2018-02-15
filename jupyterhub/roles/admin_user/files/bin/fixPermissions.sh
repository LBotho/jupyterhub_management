#!/bin/bash
set -e

if [ $# -lt 1 ]; then
  echo "Le script prend 1 argument : ./fixPermissions.sh dossie"
  exit 1
fi

if [ -d "$1" ]
then
  chown jhubuser:jhubuser -R $1
  exit 0
else
  exit 1
fi
