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

## Fonctionnalités implémentées

- Affichage des albums
- Détail des albums
- Détail d'un artiste avec ses albums
- Recherche avancée dans les albums :
  - Par nom d'album
  - Par nom de compositeur
  - Par nom d'interprète
  - Par genre
  - Par année
- Panel admin avec :
  - CRUD utilisateur
  - CRUD album
  - CRUD artiste
  - CRUD genre
- Inscription / Connexion utilisateur
- CRUD playlist pour un utilisateur ( ajout d'autant de playlist qu'il souhaite, modification et suppression de ces playlists )
- Système de notation des albums
- Pagination de l'accueil

## Mise en place sur le projet

- Module de test
- Utilisation d'une arborescence de projet type PRO
- Utilisation des namespaces
- Utilisation d'un provider YML, utilisable même pendant que le serveur tourne
- Utilisation d'un autoloader
- SGBD - Driver sous sqlite ( PDO )
- Utilisation des sessions pour la gestion utilisateur dans toute l'application
- Mise en place CSS
- Mise en place bash : *L'utilisation des fichiers bash seront décrit dans la partie ***Déploiement***.*
  - Mise en place d'un bash de configuration qui vous installe les librairies utiles pour vous faire toourner l'applciation, on recence les librairies suivantes :
    - PHP
    - php_pdo
    - pdo_sqlite
  - Mise en place d'un bash de lancement de serveur
  - Mise en place d'un bash pour le provider  et la base de données
  - Mise en place d'un bash pour le lancement des tests
- Mise en place d'une partie UML :
  - Diagramme de classe sur le modèle
  - Diagramme de séquence sur différentes fonctionnalités décrites ci-dessus
  - Diagramme d'activité sur différentes fonctionnalités décrites ci-dessus
  - MCD de la base de données
- Documentation des fichiers ( modèle BDD, CRUD, autoloader, provider YML, etc ... )

---

## Déploiement

***Pré-requis : merci de faire tourner l'application sur un OS Linux*.**

***Par défaut, la BDD dans la branche main est reset, si vous clonez le projet, la BDD sera existante et sans datas en plus que ce qu'elle a besoin pour faire tourner l'application. Si vous voulez simplement lancer l'application, passer l'étape sur la config BDD dans les instructions*.**

Pour déployer l'application, merci de suivre les instructions suivantes :

- cloner le projet sur votre machine en local

### INSTALL UTILES CONFIG

- Si PHP n'est pas sur votre machine. Merci de vous en assurer avant de suivre les instructions :

- Soit vous suivez l'instruction suivante :

- ```bash
  bash Bash/bash_config.sh
  ```

  Qui vous permettra d'installer php, pdo et pdo_sqlite

- Soit vous installer php vous-mêmes :

- ```php
  php --version
  ```

Si aucune version de PHP n'est trouvable, merci de l'installer en suivant le guide ici : <https://www.php.net/manual/fr/install.php>

Dans le cas où une version est trouvée, continuez dans l'avancement des commandes ci-dessous :

### BDD CONFIG

- Vérifier l'existence d'un fichier **.sqlite3** dans le chemin : *Database/DatabaseScripts/* ( par défaut la BDD est reset, vous n'avez pas besoin de lire cette partie )

Soit vous pouvez reset la BDD avec la commande :

- ```bash
  bash Bash/bash_destruction.sh
  ```
  
  Qui supprime la BDD complètement

Et la peupler avec

- ```bash
  bash Bash/bash_insertion.sh
  ```

  Qui créer le fichier de la BDD si il n'existe pas et le peuple ou qui peuple simplement la BDD si le fichier existe déjà

Soit vous peupler simplement la BDD avec d'autres fichiers YML en exécutant simplement la commande :

- ```bash
  bash Bash/bash_insertion.sh
  ```

- Poursuivez en écrivant un ou plusieurs des fichiers proposées

### LANCEMENT DE L'APPLICATION - SERVEUR

- Lancer le serveur avec la commande suivante :

- ```bash
  bash Bash/serveur.php
  ```

### CONNEXION APPLICATION

Par défaut, vous avez deux utilisateurs de test, un utilisateur admin :

- Connectez vous avec les logins "admin", "admin" !

Et un utilisateur lambda :

- Connectez vous avez les logins "steven", "steven" !

Bonne découverte :)

## Développeurs notes

Pour le bon développement et le déploiement de l'application sur de nouvelles features, merci de créer vos testes unitaires à exécuter avec la commande :

- ```bash
  bash Bash/bash_tests.sh
  ```
