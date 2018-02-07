# Installation automatique jupyterhub, nbgrader et portail de management

> **Note:**
> Projet réalisé dans le cadre de notre projet de fin de quatrième année à l'ISEN Brest
> Toutes les critiques sont bienvenues !
> Loïc Bothorel & Paul Michaud


#### Prérequis
  - Mot de passe root du serveur distant
  - 2.5 Go dispo
  - 2 sous domaines "management.\*" et "jupyterhub.\*"
  - Le certificat SSL pour jupyterhub.*
  - Ubuntu 16.04

#### Sur le serveur local
Génération d'une clé ssh et copie sur le serveur distant.
```
  ssh-keygen -t rsa -b 4096 -C "toto@isen.fr"
  ssh-add id_rsa
  ssh-copy-id -i id_rsa root@SERVEURDISTANT
```
Lancement du playbook d'installation en précisant l'adresse du serveur distant.
```
ansible-playbook site.yml --extra-vars "variable_host=HOSTDISTANT" -u root

```
Pour lancer en dry run
```
aansible-playbook site.yml --extra-vars "variable_host=HOSTDISTANT" -u root --check
```

#### Password 
Vous pouvez changer les mots de passes dans le fichier group_vars/all