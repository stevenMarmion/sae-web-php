<?php

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use App\Autoloader\Autoloader;
use PDO;
use PDOException;

Autoloader::register();

class CrudFavoris {

    private $db;

    /**
     * Constructeur de la classe CrudFavoris.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $db La connexion à la base de données.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Ajoute une musique aux favoris d'un utilisateur.
     *
     * @param int $idUtilisateur L'ID de l'utilisateur.
     * @param int $idMusique L'ID de la musique.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterFavori(int $idUtilisateur, int $idMusique) {
        try {
            $query = "INSERT INTO FAVORIS (idU, idM) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idUtilisateur, $idMusique]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime une musique des favoris d'un utilisateur.
     *
     * @param int $idUtilisateur L'ID de l'utilisateur.
     * @param int $idMusique L'ID de la musique.
     * @return bool True si la suppression est réussie, False sinon.
     */
    public function supprimerFavori(int $idUtilisateur, int $idMusique) {
        try {
            $query = "DELETE FROM FAVORIS WHERE idU = ? AND idM = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idUtilisateur, $idMusique]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère toutes les musiques favorites d'un utilisateur.
     *
     * @param int $idUtilisateur L'ID de l'utilisateur.
     * @return array Un tableau contenant les musiques favorites de l'utilisateur.
     */
    public function obtenirFavorisParUtilisateur(int $idUtilisateur) {
        $query = "SELECT * FROM FAVORIS WHERE idU = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idUtilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie si une musique est déjà dans les favoris d'un utilisateur.
     *
     * @param int $idUtilisateur L'ID de l'utilisateur.
     * @param int $idMusique L'ID de la musique.
     * @return bool True si la musique est dans les favoris, False sinon.
     */
    public function estFavori(int $idUtilisateur, int $idMusique) {
        $query = "SELECT * FROM FAVORIS WHERE idU = ? AND idM = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idUtilisateur, $idMusique]);
        return $stmt->rowCount() > 0;
    }
}


?>