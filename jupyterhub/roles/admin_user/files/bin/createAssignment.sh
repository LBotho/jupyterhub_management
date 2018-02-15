#!/bin/bash
set -e

if [ $# -lt 4 ]; then
  echo "Le script prend au moins 4 arguments : nomDevoir nomCours sourceDrive yearGroup [subgroup1] [subgroup2] ..."
  exit 1
fi

#nomCours: Le nom du dossier qui comportera les playbooks (On peut mettre le dossier du drive sans les tirets et en minuscule par exemple)
#nomDevoir: Le nom du devoir à faire par les élèves, ex : cplusplus_cir2_groupe2
#sourceDrive: /home/jhubuser/cours/__notebooks__/ $label le nom du dossier qui a été copié depuis le drive

nomDevoir=$1
shift
nomCours=$1
shift
sourceDrive=$1
shift

courseDir="/home/jhubuser/groupes/"

for arg in "$@"
do
  courseDir="$courseDir$arg/"
done

sourceDir="source/"
fullSourceDir=$courseDir$sourceDir$nomCours
mkdir $fullSourceDir

ln -s /home/jhubuser/cours/__notebooks__/$sourceDrive/* $fullSourceDir


(cd "$courseDir" && exec  nbgrader assign --create $nomCours)
(cd "$courseDir" && exec  nbgrader release $nomCours --course $nomDevoir --force)

chown jhubuser:jhubuser -R $fullSourceDir

if cd "$courseDir" && nbgrader db assignment list | grep -w "$nomCours"
then
  exit 0
else
  exit 1
fi
