<?php


namespace Database\DatabaseConnection;

require_once __DIR__ . '/../../App/Autoloader/autoloader.php';

use Throwable;
use PDO;
use PDOException;
use App\Autoloader\Autoloader;
use App\Parser\YamlParser;
use Database\DatabaseConnection\ConnexionBDD;

Autoloader::register();

// Instancie la tableau argv
global $argv;

$instance = new ConnexionBDD();
$tables = new InstancesTables($instance::obtenir_connexion());
$tables->create_tables($instance::obtenir_connexion(), $argv);

class InstancesTables {

    private static $db;

    public function __construct(PDO $instance) { 
        self::$db = $instance;
    }


    /**
     * Crée les tables nécessaires dans la base de données en exécutant le contenu d'un fichier SQL.
     *
     * @param PDO $db L'instance PDO à utiliser.
     */
    public function create_tables(PDO $db, $argv) {
        // Chemin vers le fichier SQL
        $fichierSQLCreate = __DIR__ . '/../DatabaseScripts/creation.sql';
        $fichierSQLInsertAdmin = __DIR__ . '/../DatabaseScripts/insertion.sql';

        try {
            if (file_exists($fichierSQLCreate)) {

                $query = "SELECT COUNT(name) AS tableCount FROM sqlite_master WHERE type='table'";
                $statement = $db->query($query);
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                $tableCount = $result['tableCount'];
                if ($tableCount > 0) {
                    $this->init_DB_insertion($argv);
                }
                else {
                    $sqlScript = file_get_contents($fichierSQLCreate);
                    $db->exec($sqlScript);
                    echo "\n>> [Tables créées avec succès]\n";

                    $sqlScript = file_get_contents($fichierSQLInsertAdmin);
                    $db->exec($sqlScript);
                    echo "\n>> [Insert admin intégrée avec succès]\n";
    
                    $this->init_DB_insertion($argv);
                }
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    function recup_argv($argv) {
        echo "\n>> [Instanciation de la BDD avec les paramètres d'exécution suivant...\n", print_r($argv), "]\n" ;
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

        foreach ($datas as $album) {
            try {
                // Insérer dans la table ALBUMS
                $queryAlbums = "INSERT INTO ALBUMS (id, img, dateDeSortie, titre) VALUES (?, ?, ?, ?)";
                $stmtAlbums = self::$db->prepare($queryAlbums);
                $stmtAlbums->execute([$album['entryId'], $album['img'], $album['releaseYear'], $album['title']]);
            } catch (Throwable $th) {echo "\n>> [Pas d'insertion, duplication repérée dans la table ALBUMS]\n";}

            try {
                // Insérer dans la table ARTISTES
                $queryArtiste = "INSERT INTO ARTISTES (nomA) VALUES (?)";
                $stmtArtiste = self::$db->prepare($queryArtiste);
                $stmtArtiste->execute([$album['by']]);
            } catch (Throwable $th) {echo "\n>> [Pas d'insertion, duplication repérée dans la table ARTISTES]\n";}

            try {
                // Récupère l'id de l'artiste fraichement inséré 
                $queryIdArtiste = "SELECT idA from ARTISTES where nomA = :nomA";
                $stmtIdArtiste = self::$db->prepare($queryIdArtiste);
                $stmtIdArtiste->bindParam(':nomA', $album['by'], PDO::PARAM_STR);
                $stmtIdArtiste->execute();
                $idArtiste = $stmtIdArtiste->fetchColumn();
            } catch (Throwable $th) {echo "\n>> [Pas d'insertion, duplication repérée dans la table ARTISTES]\n";}

            // Insérer dans la table GENRE
            foreach ($album['genre'] as $genre) {
                try {
                    $queryGenre = "INSERT INTO GENRE (nomG) VALUES (?)";
                    $stmtGenre = self::$db->prepare($queryGenre);
                    $stmtGenre->execute([$genre]);
                } catch (Throwable $th) {echo "\n>> [Pas d'insertion, duplication repérée dans la table GENRE]\n";}

                try {
                    // Récupère l'id du genre fraichement inséré 
                    $queryIdGenre = "SELECT idG from GENRE where nomG = :nomG";
                    $stmtIdGenre = self::$db->prepare($queryIdGenre);
                    $stmtIdGenre->bindParam(':nomG', $genre, PDO::PARAM_STR);
                    $stmtIdGenre->execute();
                    $idGenre = $stmtIdGenre->fetchColumn();
                } catch (Throwable $th) {echo "\n>> [Pas d'insertion, duplication repérée dans la table GENRE]\n";}

                try {
                    // Insérer dans la table ETRE
                    $queryEtre = "INSERT INTO ETRE (idAl, idG) VALUES (?, ?)";
                    $stmtEtre = self::$db->prepare($queryEtre);
                    $stmtEtre->execute([$album['entryId'], $idGenre]);
                } catch (Throwable $th) {echo "\n>> [Pas d'insertion, duplication repérée dans la table ETRE]\n";}
            }

            try {
                // Insérer dans la table COMPOSER
                $queryComposer = "INSERT INTO COMPOSER (idAl, idA) VALUES (?, ?)";
                $stmtComposer = self::$db->prepare($queryComposer);
                $stmtComposer->execute([$album['entryId'], $idArtiste]);
            } catch (Throwable $th) {echo "\n>> [Pas d'insertion, duplication repérée dans la table COMPOSER]\n" ;}
    
            try {
                // Insérer dans la table INTERPRETER
                $queryInterpreter = "INSERT INTO INTERPRETER (idAl, idA) VALUES (?, ?)";
                $stmtInterpreter = self::$db->prepare($queryInterpreter);
                $stmtInterpreter->execute([$album['entryId'], $idArtiste]);
            } catch (Throwable $th) {echo "\n>> [Pas d'insertion, duplication repérée dans la table INTERPRETER]\n" ;}
        }
        echo "\n>> [Importation des données terminée]\n";
    }
}