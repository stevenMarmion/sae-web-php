<?php

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use PDO;
use PDOException;

Autoloader::register();

class CrudPosseder{
    private $db;

    /**
     * Constructeur de la classe CrudPosseder.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $db La connexion à la base de données.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Ajoute une relation entre un utilisateur et une playlist dans la base de données.
     *
     * @param int $idU L'ID de la musique.
     * @param int $idAlbum L'ID du genre.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterRelation(int $idU, int $idAlbum) {
        try {
            $query = "INSERT INTO POSSEDER (idU, idPlaylist) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idU, $idAlbum]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime une relation entre un utilisateur et une playlist dans la base de données.
     *
     * @param int $idU L'ID de la musique.
     * @param int $idAlbum L'ID du genre.
     * @return bool True si la suppression est réussie, False sinon.
     */
    public function supprimerRelation(int $idU, int $idAlbum) {
        try {
            $query = "DELETE FROM POSSEDER WHERE idU = ? AND idPlaylist = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idU, $idAlbum]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>