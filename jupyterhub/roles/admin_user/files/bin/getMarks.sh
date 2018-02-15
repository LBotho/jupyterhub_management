#!/bin/bash
set -e

if [ $# -lt 3 ]; then
  echo "Le script prend au moins 3 arguments : nomDevoir nomCours group [subgroup1] [subgroup2] ..."
  exit 1
fi

nomDevoir=$1
shift
nomCours=$1
shift

courseDir="/home/jhubuser/groupes/"

for arg in "$@"
do
  courseDir="$courseDir$arg/"
done

(cd $courseDir && nbgrader collect $nomDevoir --course $nomCours --update )
(cd $courseDir && nbgrader autograde $nomDevoir --course $nomCours)
(cd $courseDir && nbgrader export --to $nomDevoir.csv)

chown jhubuser:jhubuser $courseDir$nomDevoir.csv

if [ -f "$courseDir/$nomDevoir.csv" ]; then
  echo "Les notes ont été exportées avec succès"
else
  echo "Problème lors de l'exportation des notes"
fi
