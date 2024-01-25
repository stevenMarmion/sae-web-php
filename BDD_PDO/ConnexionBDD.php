<?php

/**
 * Classe ConnexionBD
 * 
 * Cette classe représente la connexion à la base de données et contient des méthodes pour initialiser la base de données, 
 * créer les tables, insérer des données et effectuer des requêtes.
 */
namespace BD;

use PDO;
use PDOException;

/**
 * Classe ConnexionBDD
 * 
 * Cette classe représente la connexion à la base de données.
 * Elle initialise la base de données, crée les tables nécessaires,
 * insère les données de quiz, questions, réponses et choix, si la base de données est vide.
 */
class ConnexionBDD {
    private static $db = null;

    /**
     * Constructeur de la classe ConnexionBDD.
     * Initialise la connexion à la base de données et crée les tables si elles n'existent pas.
     */
    public function __construct() {
        date_default_timezone_set('Europe/Paris');
        try {
            if (self::$db === null) {
                self::$db = $this->init_DB();
            }

        } catch (PDOException $e) {}
    }

    /**
     * Obtient la connexion à la base de données.
     *
     * @return PDO La connexion à la base de données.
     */
    public static function obtenir_connexion() {
        if (self::$db === null) {
            try {
                new ConnexionBDD();
            } catch (PDOException $e) {
                die('Erreur de connexion à la base de données : ' . $e->getMessage());
            }
        }
        return self::$db;
    }

    /**
     * Initialise la connexion à la base de données.
     * Si la connexion n'est pas déjà établie, crée une nouvelle instance de PDO et configure les attributs.
     * 
     * @return PDO L'objet PDO représentant la connexion à la base de données.
     */
    function init_DB() {
        if (self::$db == null) {
            $cheminFichierSQLite = __DIR__ . '/../BDD/BD_app_Musique.sqlite3';
            self::$db = new PDO('sqlite:' . $cheminFichierSQLite);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$db;
    }

    /**
     * Crée les tables nécessaires dans la base de données.
     * Cette fonction exécute des requêtes SQL pour créer les tables "UTILISATEUR", "GENRE", "ARTISTES", "MUSIQUES", "ETRE" et "FAVORIS".
     * Chaque table est définie avec ses colonnes, contraintes de clé primaire et étrangère.
     *
     * @return void
     */
    function create_tables() {
        // Table UTILISATEUR
        self::$db->exec("CREATE TABLE UTILISATEUR (
            idU INTEGER NOT NULL,
            isAdmin INTEGER NOT NULL,
            pseudo TEXT NOT NULL UNIQUE,
            mdp TEXT NOT NULL,
            adresseMail TEXT,
            PRIMARY KEY(idU AUTOINCREMENT)
        );");

        /**
         * Crée la table "GENRE" dans la base de données si elle n'existe pas déjà.
         * La table "GENRE" contient les colonnes suivantes :
         * - idG : identifiant unique du genre (clé primaire)
         * - nomG : nom du genre
         */
        self::$db->exec("CREATE TABLE GENRE (
            idG INTEGER NOT NULL,
            nomG INTEGER NOT NULL UNIQUE,
            PRIMARY KEY(idG AUTOINCREMENT)
        );");

        /**
         * Crée la table "ARTISTES" dans la base de données si elle n'existe pas déjà.
         * La table "ARTISTES" contient les colonnes suivantes :
         * - idA : identifiant unique de l'artiste (clé primaire)
         * - nomA : nom de l'artiste
         */
        self::$db->exec("CREATE TABLE ARTISTES (
            idA INTEGER NOT NULL,
            nomA TEXT NOT NULL,
            PRIMARY KEY(idA)
        );");

        /**
         * Crée la table "MUSIQUES" dans la base de données si elle n'existe pas déjà.
         * La table "MUSIQUES" contient les colonnes suivantes :
         * - id : identifiant unique de la musique (clé primaire)
         * - img : données binaires de l'image associée à la musique
         * - dateDeSortie : date de sortie de la musique
         * - titre : titre de la musique
         * - idCompositeur : clé étrangère faisant référence à la table "ARTISTES" pour le compositeur
         * - idInterprete : clé étrangère faisant référence à la table "ARTISTES" pour l'interprète
         */
        self::$db->exec("CREATE TABLE MUSIQUES (
            id INTEGER NOT NULL,
            img BLOB,
            dateDeSortie INTEGER NOT NULL,
            titre TEXT NOT NULL,
            idCompositeur INTEGER NOT NULL,
            idInterprete INTEGER NOT NULL,
            FOREIGN KEY(idCompositeur) REFERENCES ARTISTES(idA),
            FOREIGN KEY(idInterprete) REFERENCES ARTISTES(idA),
            PRIMARY KEY(id)
        );");

        /**
         * Crée la table "ETRE" dans la base de données si elle n'existe pas déjà.
         * La table "ETRE" contient les colonnes suivantes :
         * - idM : clé étrangère faisant référence à la table "MUSIQUES"
         * - idG : clé étrangère faisant référence à la table "GENRE"
         * Les colonnes idM et idG forment ensemble la clé primaire de la table "ETRE".
         */
        self::$db->exec("CREATE TABLE ETRE (
            idM INTEGER NOT NULL,
            idG INTEGER NOT NULL,
            FOREIGN KEY(idG) REFERENCES GENRE(idG),
            FOREIGN KEY(idM) REFERENCES MUSIQUES(id),
            PRIMARY KEY(idM, idG)
        );");

        /**
         * Crée la table "FAVORIS" dans la base de données si elle n'existe pas déjà.
         * La table "FAVORIS" contient les colonnes suivantes :
         * - idU : clé étrangère faisant référence à la table "UTILISATEUR"
         * - idM : clé étrangère faisant référence à la table "MUSIQUES"
         * Les colonnes idU et idM forment ensemble la clé primaire de la table "FAVORIS".
         */
        self::$db->exec("CREATE TABLE FAVORIS (
            idU INTEGER NOT NULL,
            idM INTEGER NOT NULL,
            FOREIGN KEY(idM) REFERENCES MUSIQUES(id),
            FOREIGN KEY(idU) REFERENCES UTILISATEUR(idU),
            PRIMARY KEY(idU, idM)
        );");
    }
}

// Test de création et d'instanciation
$instance = new ConnexionBDD();
$instance->create_tables();

?>
