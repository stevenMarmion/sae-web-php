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
     * Crée les tables nécessaires dans la base de données en exécutant le contenu d'un fichier SQL.
     *
     * @param PDO $db L'instance PDO à utiliser.
     */
    public function create_tables(PDO $db) {
        // Chemin vers le fichier SQL
        $fichierSQL = __DIR__ . '/../BDD/creation.sql';

        try {
            $sqlScript = file_get_contents($fichierSQL);
            $db->exec($sqlScript);

            echo "Tables créées avec succès.";
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
}

// Test de création et d'instanciation
$instance = new ConnexionBDD();
$instance->create_tables($instance::obtenir_connexion());

?>
