#!/bin/bash
set -e

if [ $# -lt 2 ]; then
    echo "Le script prend au moins 2 arguments : userId yeargroup [subgroup1] [subgroup2] ..."
    exit 1
fi

workingDir="/home/jhubuser/groupes/"

user=$1
shift

for arg in "$@"
do
    workingDir="$workingDir$arg/"
done

if [ ! -d "$workingDir" ]; then
  echo "Le groupe $workingDir n'existe pas"
  exit 1
fi

(cd "$workingDir" && exec  nbgrader db student remove "$user" --force)

if cd "$workingDir" && nbgrader db student list | grep -w "$user"
then
    exit 1
else
    echo 0
fi
