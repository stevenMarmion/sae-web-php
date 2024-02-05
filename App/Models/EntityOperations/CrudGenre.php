<?php

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \App\Models\Genre;
use \App\Models\EntityOperations\CrudEtre;
use PDO;
use PDOException;

Autoloader::register();

class CrudGenre {

    private $db;

    /**
     * Constructeur de la classe CrudGenre.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $db La connexion à la base de données.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Ajoute un nouveau genre à la base de données depuis une donnée yml.
     *
     * @param string $genreData Les données du genre à ajouter.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterGenreFromYml(string $genreData) {
        try {
            $query = "INSERT INTO GENRE (nomG) VALUES (?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$genreData]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Ajoute un nouveau genre à la base de données depuis une donnée objet.
     *
     * @param Genre $genreData Les données du genre à ajouter.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterGenreFromObject(Genre $genreData) {
        try {
            $query = "INSERT INTO GENRE (nomG) VALUES (?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$genreData->getNomGenre()]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime un genre de la base de données en fonction de son ID.
     *
     * @param int $genreId L'ID du genre à supprimer.
     * @return bool True si la suppression est réussie, False sinon.
     */
    public function supprimerGenre(int $genreId) {
        try {
            $crudEtre = new CrudEtre($this->db);

            $listeAlbums = $crudEtre->obtenirMusiquesParGenre($genreId);

            foreach ($listeAlbums as $album) {
                $actionValid = $crudEtre->supprimerRelation($album["idAl"], $genreId);
            }
            $query = "DELETE FROM GENRE WHERE idG = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$genreId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Modifie les données d'un genre dans la base de données en fonction de son ID.
     *
     * @param int $genreId L'ID du genre à modifier.
     * @param Genre $newGenreData Les nouvelles données du genre.
     * @return bool True si la modification est réussie, False sinon.
     */
    public function modifierGenre(int $genreId, Genre $newGenreData) {
        try {
            $query = "UPDATE GENRE SET nomG = ? WHERE idG = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$newGenreData->getNomGenre(), $genreId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère tous les genres de la base de données.
     *
     * @return array Un tableau contenant tous les genres.
     */
    public function obtenirTousGenres() {
        $query = "SELECT * FROM GENRE";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un genre en fonction de son ID.
     *
     * @param int $genreId L'ID du genre à récupérer.
     * @return array|false Les données du genre ou False si le genre n'est pas trouvé.
     */
    public function obtenirGenreParId(int $genreId) {
        $query = "SELECT * FROM GENRE WHERE idG = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$genreId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }

    public function obtenirGenreParNom(string $nomGenre) {
        $query = "SELECT * FROM GENRE WHERE nomG = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$nomGenre]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }

    public function obtenirGenreParYml(string $genre) {
        $queryIdGenre = "SELECT idG from GENRE where nomG = :nomG";
        $stmtIdGenre = self::$db->prepare($queryIdGenre);
        $stmtIdGenre->bindParam(':nomG', $genre, PDO::PARAM_STR);
        $stmtIdGenre->execute();
        return $stmtIdGenre->fetchColumn() ?: false;
    }
}


?>