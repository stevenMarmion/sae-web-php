<?php

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use App\Autoloader\Autoloader;
use App\Models\Album;
use PDO;
use PDOException;

Autoloader::register();

class CrudAlbum {

    private $db;

    /**
     * Constructeur de la classe CrudAlbum.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $db La connexion à la base de données.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Ajoute un nouvel album à la base de données depuis une donnée yml.
     *
     * @param array $albumData Les données de l'album à ajouter.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterAlbumFromYml(array $albumData) {
        try {
            $query = "INSERT INTO ALBUMS (img, dateDeSortie, titre) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$albumData['img'], $albumData['dateDeSortie'], $albumData['titre']]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Ajoute un nouvel album à la base de données depuis une donnée Album en objet.
     *
     * @param Album $albumData Les données de l'album à ajouter.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterAlbumFromObject(Album $albumData) {
        try {
            $query = "INSERT INTO ALBUMS (img, dateDeSortie, titre) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$albumData->getImg(), $albumData->getDateSortie(), $albumData->getTitre()]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime un album de la base de données en fonction de son ID.
     *
     * @param int $albumId L'ID de l'album à supprimer.
     * @return bool True si la suppression est réussie, False sinon.
     */
    public function supprimerAlbum(int $albumId) {
        try {
            $query = "DELETE FROM ALBUMS WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$albumId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Modifie les données d'un album dans la base de données en fonction de son ID.
     *
     * @param int $albumId L'ID de l'album à modifier.
     * @param Album $newAlbumData Les nouvelles données de l'album.
     * @return bool True si la modification est réussie, False sinon.
     */
    public function modifierAlbum(int $albumId, Album $newAlbumData) {
        try {
            $query = "UPDATE ALBUMS SET img = ?, dateDeSortie = ?, titre = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$newAlbumData->getImg(), 
                            $newAlbumData->getDateSortie(), 
                            $newAlbumData->getTitre(),
                            $albumId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère tous les albums de la base de données.
     *
     * @return array Un tableau contenant tous les albums.
     */
    public function obtenirTousAlbums() {
        $query = "SELECT * FROM ALBUMS";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un album en fonction de son ID.
     *
     * @param int $albumId L'ID de l'album à récupérer.
     * @return array|false Les données de l'album ou False si l'album n'est pas trouvé.
     */
    public function obtenirAlbumParId(int $albumId) {
        $query = "SELECT * FROM ALBUMS WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$albumId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }
}

?>
