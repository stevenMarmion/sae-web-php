<?php

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';
// require_once __DIR__ . '/../../Models/EntityOperations/CrudArtiste.php';

use \App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudArtiste;
use App\Models\Album;
use PDO;
use PDOException;

Autoloader::register();

class CrudComposer {

    private $db;
    private CrudArtiste $crudArtiste;

    /**
     * Constructeur de la classe CrudComposer.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $db La connexion à la base de données.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
        $this->crudArtiste = new CrudArtiste($db);
    }

    /**
     * Ajoute un nouveau compositeur à la base de données.
     *
     * @param int $albumId L'ID de l'album associé au compositeur.
     * @param int $artisteId L'ID de l'artiste associé au compositeur.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterCompositeur(int $albumId, int $artisteId) {
        try {
            $query = "INSERT INTO COMPOSER (idAl, idA) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$albumId, $artisteId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère tous les compositeurs associés à un album.
     *
     * @param int $albumId L'ID de l'album pour lequel récupérer les compositeurs.
     * @return array Un tableau contenant tous les compositeurs associés à l'album.
     */
    public function obtenirCompositeursParAlbum(int $albumId) {
        $query = "SELECT * FROM COMPOSER WHERE idAl = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$albumId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenirAlbumdParCompositeur(int $artisteId) {
        $query = "SELECT * FROM COMPOSER WHERE idA = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$artisteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function modifierCompositeur(int $albumId, Album $newAlbumData, int $indexComp, int $compositeur) {
        $query = "UPDATE COMPOSER SET idA = ? WHERE idAl = ? and idA = ?";
        $stmt = $this->db->prepare($query);
        $idArtiste = $this->crudArtiste->obtenirArtisteParNom($newAlbumData->getCompositeurs()[$indexComp])["idA"];
        $stmt->execute([$idArtiste,
                        $albumId,
                        $compositeur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Supprime un compositeur de la base de données en fonction de l'ID de l'album et de l'ID de l'artiste.
     *
     * @param int $albumId L'ID de l'album pour lequel supprimer le compositeur.
     * @param int $artisteId L'ID de l'artiste associé au compositeur.
     * @return bool True si la suppression est réussie, False sinon.
     */
    public function supprimerCompositeur(int $albumId, int $artisteId) {
        try {
            $query = "DELETE FROM COMPOSER WHERE idAl = ? AND idA = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$albumId, $artisteId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function supprimerAllAlbum(int $albumId) {
        try {
            $query = "DELETE FROM COMPOSER WHERE idAl = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$albumId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

?>
