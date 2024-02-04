<?php

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';
// require_once __DIR__ . '/../../Models/EntityOperations/CrudArtiste.php';
// require_once __DIR__ . '/../../Models/Album.php';

use \App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudArtiste;
use App\Models\Album;
use PDO;
use PDOException;

Autoloader::register();

class CrudInterprete {

    private $db;
    private CrudArtiste $crudArtiste;

    /**
     * Constructeur de la classe CrudInterprete.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $db La connexion à la base de données.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
        $this->crudArtiste = new CrudArtiste($db);
    }

    /**
     * Ajoute un nouvel interprète à la base de données.
     *
     * @param int $albumId L'ID de l'album associé à l'interprète.
     * @param int $artisteId L'ID de l'artiste associé à l'interprète.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterInterprete(int $albumId, int $artisteId) {
        try {
            $query = "INSERT INTO INTERPRETER (idAl, idA) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$albumId, $artisteId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function modifierInterprete(int $albumId, Album $newAlbumData, int $indexInt, int $interprete) {
        $query = "UPDATE INTERPRETER SET idA = ? WHERE idAl = ? and idA = ?";
        $stmt = $this->db->prepare($query);
        $idArtiste = $this->crudArtiste->obtenirArtisteParNom($newAlbumData->getInterpretes()[$indexInt])["idA"];
        $stmt->execute([$idArtiste,
                        $albumId,
                        $interprete]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les interprètes associés à un album.
     *
     * @param int $albumId L'ID de l'album pour lequel récupérer les interprètes.
     * @return array Un tableau contenant tous les interprètes associés à l'album.
     */
    public function obtenirInterpretesParAlbum(int $albumId) {
        $query = "SELECT * FROM INTERPRETER WHERE idAl = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$albumId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenirAlbumsParInterprete(int $artisteId) {
        $query = "SELECT * FROM INTERPRETER WHERE idA = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$artisteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime un interprète de la base de données en fonction de l'ID de l'album et de l'ID de l'artiste.
     *
     * @param int $albumId L'ID de l'album pour lequel supprimer l'interprète.
     * @param int $artisteId L'ID de l'artiste associé à l'interprète.
     * @return bool True si la suppression est réussie, False sinon.
     */
    public function supprimerInterprete(int $albumId, int $artisteId) {
        try {
            $query = "DELETE FROM INTERPRETER WHERE idAl = ? AND idA = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$albumId, $artisteId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function supprimerAllAlbum(int $albumId) {
        try {
            $query = "DELETE FROM INTERPRETER WHERE idAl = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$albumId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
