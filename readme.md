# SAE WEB PHP - Musics 2024 

## Présentation

A partir des données que vous trouverez dans le fichier fixtures.zip dans Celene, il vous est demandé de
réaliser une application présentant le contenu de cette base d'albums de musique. 

Le fichier fixtures.zip contient quelques albums et artistes avec des pochettes d'albums. 

## Fonctionalités attendues

- Modèle de la BD de musiques  
- Affichage des albums
- Détail des albums
- Détail d'un artiste avec ses albums
- Edition d'un album
- Recherche un peu plus avancée dans les albums (par artiste, année, genre, etc.)

## Fonctionalités souhaitées
Edition/Suppression/Update Albums

## Fonctionnalités possibles

- Login / Users
- Playlist par user
- Noter les albums

## Organisation et délais

- Par groupes de 3

## Contraintes

- Organisation du code dans une arboresence cohérente
- Utilisation des objets
- Utilisation des namespaces
- Utilisation d'un autoloader
- Utilisation de PDO avec base de données sqlite

## Dépôt 

- Dépôt gitlab/github obligatoire 
- Enseignants SAé à mettre en reporter sur le dépôt
- Soutenances à définir avec votre chargé de SAé

## Membres du groupe 
- MARMION Steven
- SIMON Gael
- DEPONT Samuel

---
# Déploiement

Pour déployer l'application, merci de suivre les instructions suivantes : 
- cloner le projet sur votre machine en local
- Si PHP n'est pas sur votre machine. Merci de vous en assurer avant de suivre les instructions :
- ```php
  php --version
  ```
Si aucune version de PHP n'est trouvable, merci de l'installer en suivant le guide ici : https://www.php.net/manual/fr/install.php
Dans le cas où une verison est trouvée, continuez l'avancement des commandes ci-dessous :
- Vérifier l'existence d'un fichier **.sqlite3** dans le chemin : *Database/DatabaseScripts/* 
Si il n'y aucun fichier :
- ```bash
  bash Bash/bash_insertion.sh
  ```
- Poursuivez en écrivant un ou plusieurs des fichiers proposées
- Lancer le serveur avec la commande suivante :
- ```bash
  bash Bash/serveur.php
  ```
- Ouvrez l'URL qui vous ai proposé
- Complétez l'URL par */Public*

Bonne découvert :) 
