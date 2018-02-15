#!/bin/bash
set -e

if [ $# -lt 4 ]; then
    echo "Le script prend au moins 4 arguments : user firstname lastname yeargroup [subgroup1] [subgroup2] ..."
    exit 1
fi

workingDir="/home/jhubuser/groupes/"

user=$1
shift
firstname=$1
shift
lastname=$1
shift
for arg in "$@"
do
    workingDir="$workingDir$arg/"
done

if [ ! -d "$workingDir" ]; then
  echo "Le groupe $workingDir n'existe pas"
  exit 1
fi

(cd "$workingDir" && exec  nbgrader db student add "$user" --last-name="$lastname" --first-name="$firstname")

if cd "$workingDir" && nbgrader db student list | grep -w "$user.*$lastname"
then
    exit 0
else
    exit 1
fi
