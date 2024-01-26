<?php

namespace BDD;

require_once __DIR__.'/../Parser/YamlParser.php';

use PDO;
use PDOException;
use Parser\YamlParser;

// Test de création et d'instanciation
$instance = new ConnexionBDD();
$instance->create_tables($instance::obtenir_connexion(), $argv);

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
                // Instancie le tableau argv
                global $argv;
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
    public function create_tables(PDO $db, $argv) {
        // Chemin vers le fichier SQL
        $fichierSQL = __DIR__ . '/../BDD/creation.sql';

        try {
            $sqlScript = file_get_contents($fichierSQL);
            $db->exec($sqlScript);
            $this->init_DB_insertion($argv);

            echo "Tables créées avec succès.";
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    function recup_argv($argv) {
        var_dump($argv);
        if (count($argv) < 2) {
            die("Veuillez fournir le chemin vers le fichier YAML en argument !\n");
        }
        $yamlFilePath = $argv[1];
        $datas = YamlParser::parser($yamlFilePath);
        if ($datas === false) {
            die("Erreur lors du chargement du fichier YAML.\n");
        }
        return $datas;
    }


    function init_DB_insertion($argv) {
        $datas = $this->recup_argv($argv);
        var_dump($datas);

        foreach ($datas as $album) {
            try {
                // Insérer dans la table ALBUMS
                $queryAlbums = "INSERT INTO ALBUMS (id, img, dateDeSortie, titre) VALUES (?, ?, ?, ?)";
                $stmtAlbums = self::$db->prepare($queryAlbums);
                $stmtAlbums->execute([$album['entryId'], $album['img'], $album['releaseYear'], $album['title']]);
            } catch (\Throwable $th) {var_dump("Il y a une erreur dans la table : ALBUMS");}

            try {
                // Insérer dans la table ARTISTES
                $queryArtiste = "INSERT INTO ARTISTES (nomA) VALUES (?)";
                $stmtArtiste = self::$db->prepare($queryArtiste);
                $stmtArtiste->execute([$album['by']]);
            } catch (\Throwable $th) {var_dump("Il y a une erreur dans la table : ARTISTES");}

            try {
                // Récupère l'id de l'artiste fraichement inséré 
                $queryIdArtiste = "SELECT idA from ARTISTES where nomA = :nomA";
                $stmtIdArtiste = self::$db->prepare($queryIdArtiste);
                $stmtIdArtiste->bindParam(':nomA', $album['by'], PDO::PARAM_STR);
                $stmtIdArtiste->execute();
                $idArtiste = $stmtIdArtiste->fetchColumn();
            } catch (\Throwable $th) {var_dump("Il y a une erreur dans la table : ARTISTES");}

            // Insérer dans la table GENRE
            foreach ($album['genre'] as $genre) {
                try {
                    $queryGenre = "INSERT INTO GENRE (nomG) VALUES (?)";
                    $stmtGenre = self::$db->prepare($queryGenre);
                    $stmtGenre->execute([$genre]);
                } catch (\Throwable $th) {var_dump("Il y a une erreur dans la table : GENRE");}

                try {
                    // Récupère l'id du genre fraichement inséré 
                    $queryIdGenre = "SELECT idG from GENRE where nomG = :nomG";
                    $stmtIdGenre = self::$db->prepare($queryIdGenre);
                    $stmtIdGenre->bindParam(':nomG', $genre, PDO::PARAM_STR);
                    $stmtIdGenre->execute();
                    $idGenre = $stmtIdGenre->fetchColumn();
                } catch (\Throwable $th) {var_dump("Il y a une erreur dans la table : GENRE");}

                try {
                    // Insérer dans la table ETRE
                    $queryEtre = "INSERT INTO ETRE (idAl, idG) VALUES (?, ?)";
                    $stmtEtre = self::$db->prepare($queryEtre);
                    $stmtEtre->execute([$album['entryId'], $idGenre]);
                } catch (\Throwable $th) {var_dump("Il y a une erreur dans la table : ETRE");}
            }

            try {
                // Insérer dans la table COMPOSER
                $queryComposer = "INSERT INTO COMPOSER (idAl, idA) VALUES (?, ?)";
                $stmtComposer = self::$db->prepare($queryComposer);
                $stmtComposer->execute([$album['entryId'], $idArtiste]);
            } catch (\Throwable $th) {var_dump("Il y a une erreur dans la table : COMPOSER");}
    
            try {
                // Insérer dans la table INTERPRETER
                $queryInterpreter = "INSERT INTO INTERPRETER (idAl, idA) VALUES (?, ?)";
                $stmtInterpreter = self::$db->prepare($queryInterpreter);
                $stmtInterpreter->execute([$album['entryId'], $idArtiste]);
            } catch (\Throwable $th) {var_dump("Il y a une erreur dans la table : INTERPRETER");}
        }
        echo "Importation des données terminée !";
    }
}

?>
