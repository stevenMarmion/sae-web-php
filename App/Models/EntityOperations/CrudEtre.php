<?php

declare(strict_types=1);

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudGenre;
use App\Models\Album;
use PDO;
use PDOException;

Autoloader::register();

class CrudEtre {

    private $db;

    /**
     * Constructeur de la classe CrudEtre.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $db La connexion à la base de données.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Ajoute une relation entre une musique et un genre dans la base de données.
     *
     * @param int $idMusique L'ID de la musique.
     * @param int $idGenre L'ID du genre.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterRelation(int $idMusique, int $idGenre) {
        try {
            $query = "INSERT INTO ETRE (idAl, idG) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idMusique, $idGenre]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime une relation entre une musique et un genre dans la base de données.
     *
     * @param int $idMusique L'ID de la musique.
     * @param int $idGenre L'ID du genre.
     * @return bool True si la suppression est réussie, False sinon.
     */
    public function supprimerRelation(int $idMusique, int $idGenre) {
        try {
            $query = "DELETE FROM ETRE WHERE idAl = ? AND idG = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idMusique, $idGenre]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function supprimerAllAlbum(int $idAlbum) {
        try {
            $query = "DELETE FROM ETRE WHERE idAl = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idAlbum]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function modifierRelation(int $albumId, Album $newAlbumData, int $indexGenre, int $genre) {
        $query = "UPDATE ETRE SET idG = ? WHERE idAl = ? and idG = ?";
        $stmt = $this->db->prepare($query);
        $crudGenre = new CrudGenre($this->db);
        $idGenre = $crudGenre->obtenirGenreParId($newAlbumData->getGenres()[$indexGenre])["idG"];
        $stmt->execute([$idGenre,
                        $albumId,
                        $genre]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les genres associés à une musique en fonction de son ID.
     *
     * @param int $idMusique L'ID de la musique.
     * @return array|false Un tableau contenant les genres associés à la musique.
     */
    public function obtenirGenresParMusique(int $idMusique) {
        $query = "SELECT * FROM ETRE WHERE idAl = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idMusique]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;
    }

    /**
     * Récupère toutes les musiques associées à un genre en fonction de son ID.
     *
     * @param int $idGenre L'ID du genre.
     * @return array Un tableau contenant les musiques associées au genre.
     */
    public function obtenirMusiquesParGenre(int $idGenre) {
        $query = "SELECT * FROM ETRE WHERE idG = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idGenre]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>