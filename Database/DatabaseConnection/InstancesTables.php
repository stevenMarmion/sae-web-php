<?php

declare(strict_types=1);

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

    /**
     * Constructeur de la classe InstancesTables.
     *
     * Initialise une instance de la classe InstancesTables en assignant une instance de PDO à la propriété statique $db.
     *
     * @param PDO $instance L'instance PDO à utiliser pour la connexion à la base de données.
     */
    public function __construct(PDO $instance) { 
        self::$db = $instance;
    }


    /**
     * Créer les tables nécessaires dans la base de données en exécutant le contenu d'un fichier SQL.
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

    /**
     * Récupère les données YAML à partir des arguments de la ligne de commande.
     *
     * Cette fonction récupère le chemin vers le fichier YAML à partir des arguments de la ligne de commande passés en paramètre.
     * Si le nombre d'arguments est inférieur à 2, la fonction affiche un message d'erreur et arrête l'exécution du script.
     * Ensuite, elle charge le fichier YAML à partir du chemin spécifié en utilisant la méthode parser de la classe YamlParser.
     * Si le chargement échoue (la méthode parser renvoie false), la fonction affiche un message d'erreur et arrête l'exécution du script.
     *
     * @param array $argv Les arguments de la ligne de commande.
     * @return array|false Les données YAML chargées depuis le fichier, ou false en cas d'erreur.
     */
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


    /**
     * Initialise l'insertion des données dans la base de données à partir des données YAML.
     *
     * Cette fonction récupère les données YAML à partir des arguments de la ligne de commande et les insère dans les tables correspondantes de la base de données.
     * Pour chaque album dans les données YAML, cette fonction effectue les opérations suivantes :
     *  - Insère l'album dans la table ALBUMS avec les valeurs de l'ID, de l'image, de la date de sortie et du titre.
     *  - Insère l'artiste dans la table ARTISTES avec le nom de l'artiste.
     *  - Récupère l'ID de l'artiste fraîchement inséré.
     *  - Pour chaque genre de l'album, insère le genre dans la table GENRE et insère une relation entre l'album et le genre dans la table ETRE.
     *  - Insère une relation entre l'album et l'artiste dans la table COMPOSER et une autre relation dans la table INTERPRETER.
     * En cas d'échec lors de l'insertion dans une table, la fonction affiche un message indiquant qu'il n'y a pas eu d'insertion en raison d'une duplication dans cette table.
     * Une fois toutes les opérations terminées, la fonction affiche un message indiquant que l'importation des données est terminée.
     *
     * @param array $argv Les arguments de la ligne de commande.
     */
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