<?php

namespace Database\DatabaseConnection;

require_once __DIR__ . '/../../App/Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use PDO;
use PDOException;

Autoloader::register();

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
                // Créer la connexion
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
        return self::$db; // Ajouter cette ligne pour retourner l'instance PDO
    }

    /**
     * Initialise la connexion à la base de données.
     * Si la connexion n'est pas déjà établie, crée une nouvelle instance de PDO et configure les attributs.
     * 
     * @return PDO L'objet PDO représentant la connexion à la base de données.
     */
    function init_DB() {
        if (self::$db === null) {
            try {
                $cheminFichierSQLite = __DIR__ . '/../DatabaseScripts/BD_app_Musique.sqlite3';
                self::$db = new PDO('sqlite:' . $cheminFichierSQLite);
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                $e->getMessage();
            }
        }
        return self::$db;
    }

}

?>
