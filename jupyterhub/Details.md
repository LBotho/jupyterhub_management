### Methode pour donner un exercice manuellement
Créer les élèves sur le système
```
cd /home/jhubuser/bin/ && sudo ./juseradd loic
cd /home/jhubuser/bin/ && sudo ./juseradd paul
```
Créer la classe et générer le fichier de configuration
```
mkdir -p /home/jhubuser/groupes/cir1/source
cd /home/jhubuser/groupes/cir1/ && nbgrader --generate-config
```
Ajouter les utilisateurs à la classe
```
cd /home/jhubuser/groupes/cir1/ && nbgrader db student add loic --last-name=Bothorel --first-name=Loic
cd /home/jhubuser/groupes/cir1/ && nbgrader db student add paul --last-name=Michaud --first-name=paul
```
Créer le devoir
```
mkdir /home/jhubuser/groupes/cir1/source/radioactivite_cir1
cp lenotebook.ipynb /home/jhubuser/groupes/cir1/source/radioactivite_cir1/
cd /home/jhubuser/groupes/cir1 && nbgrader assign --create radioactivite_cir1
cd /home/jhubuser/groupes/cir1 && nbgrader release radioactivite_cir1 --course radioactivite_cir1_premier
```

#### Faire le devoir sur paul & loic (car ils sont en cir1) et submit

Récupérer et noter les notebooks
```
cd /home/jhubuser/groupes/cir1 && nbgrader collect radioactivite_cir1 --course radioactivite_cir1_premier
cd /home/jhubuser/groupes/cir1 && nbgrader autograde radioactivite_cir1 --course radioactivite_cir1_premier
```
Exporter le résultat sous forme .csv
```
cd /home/jhubuser/groupes/cir1 && nbgrader export --to mygrades.csv
```

### Methode pour donner un exercice avec les scripts

Créer les élèves sur le système
```
~/bin $ ./juseradd.sh paul
~/bin $ ./juseradd.sh loic
```
Créer la classe et générer le fichier de configuration
```
~/bin $ ./createGroups.sh cir1
```
Ajouter les utilisateurs à la classe
```
~/bin $ ./addUserToGroup.sh paul Paul Michaud cir1
~/bin $ ./addUserToGroup.sh loic Loic Bothorel cir1
```
Créer le devoir
```
jhubuser@vps320837 ~/bin $ ./createAssignment.sh radioactivite_cir1 radioactivite_cir1_premier _Radioactivite cir1
```

#### Faire le devoir sur paul & loic (car ils sont en cir1) et submit

Récupérer, noter et exporter les notes
```
jhubuser@vps320837 ~/bin $ ./getMarks.sh radioactivite_cir1 radioactivite_cir1_premier cir1
```

Compte utilisé pour la synchronisation des notebooks : Penser à ne pas mettre le fichier json secret en publique
synchronisation.cours@gmail.com / isen29200

## Tests
#### Chrome
Download chromedriver from  [here](https://chromedriver.storage.googleapis.com/index.html?path=2.29/) and move it to /usr/local/bin/

#### Firefox
Download geckodriver from  [here](https://github.com/mozilla/geckodriver/releases) and move it to /usr/local/bin/

#### Install the framework
```
pip install robotframework
pip install robotframework-selenium2library
```

#### Launch tests
```
robot tests/
```
