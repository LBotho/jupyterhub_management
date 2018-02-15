#!/bin/bash
set -e

if [ $# -lt 1 ]; then
  echo "Le script prend au moins 1 argument : ./createGroups.sh yearGroup [subgroup1] [subgroup2] ..."
  exit 1
fi

newDir="/home/jhubuser/groupes/"

for arg in "$@"
do
  newDir="$newDir$arg/"
done

mkdir -p "$newDir/source"

(cd "$newDir" && exec  nbgrader --generate-config)

chown jhubuser:jhubuser -R $newDir

if [ -d "$newDir" ] && [ -f "$newDir/nbgrader_config.py" ]; then
  exit 0
else
  echo 1
fi
