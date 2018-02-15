# Script pour conserver le répertoire de travail courant (pwd)
# à la sortie de Midnight Commander (mc)
#
# on récupère l'identifiant de l'utilisateur
MC_USER=$(id -un)
# à partir de cet identifiant, on définit un fichier
# temporaire pour l'utilisateur
MC_PWD_FILE="${TMPDIR:-/tmp}/mc-$MC_USER/mc.pwd.$$"
 
# mc est lancé avec l'option -P
# elle permet de sauvegarder le dernier chemin parcouru
# dans un fichier (MC_PWD_FILE en l’occurrence)
/usr/bin/mc -P "$MC_PWD_FILE" "$@"
 
if test -r "$MC_PWD_FILE"; then
    MC_PWD="$(cat "$MC_PWD_FILE")"
    if test -n "$MC_PWD" && test -d "$MC_PWD"; then
        cd "$MC_PWD"
    fi
    unset MC_PWD
    # hack permettant d'éviter (entre autre) à l'extension "powerline"
    # d'afficher la variable MC_PWD plutôt que le chemin complet
    cd $(pwd)
fi
 
rm -f "$MC_PWD_FILE"
unset MC_PWD_FILE