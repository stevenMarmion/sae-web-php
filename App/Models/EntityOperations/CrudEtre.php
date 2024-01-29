<?php

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use App\Autoloader\Autoloader;
use PDO;
use PDOException;

Autoloader::register();

class EtreCRUD {

    private $db;

    /**
     * Constructeur de la classe EtreCRUD.
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
            $query = "INSERT INTO ETRE (idM, idG) VALUES (?, ?)";
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
            $query = "DELETE FROM ETRE WHERE idM = ? AND idG = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idMusique, $idGenre]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère tous les genres associés à une musique en fonction de son ID.
     *
     * @param int $idMusique L'ID de la musique.
     * @return array Un tableau contenant les genres associés à la musique.
     */
    public function obtenirGenresParMusique(int $idMusique) {
        $query = "SELECT * FROM ETRE WHERE idM = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idMusique]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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