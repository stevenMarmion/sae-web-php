<?php

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use App\Autoloader\Autoloader;
use PDO;
use PDOException;

Autoloader::register();

class CrudUser {

    private $db;

    /**
     * Constructeur de la classe CrudUser.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $db La connexion à la base de données.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Ajoute un nouvel utilisateur à la base de données.
     *
     * @param array $userData Les données de l'utilisateur à ajouter.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterUtilisateur(array $userData) {
        try {
            $query = "INSERT INTO UTILISATEUR (isAdmin, pseudo, mdp, adresseMail) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userData['isAdmin'], $userData['pseudo'], $userData['mdp'], $userData['adresseMail']]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime un utilisateur de la base de données en fonction de son ID.
     *
     * @param int $userId L'ID de l'utilisateur à supprimer.
     * @return bool True si la suppression est réussie, False sinon.
     */
    public function supprimerUtilisateur(int $userId) {
        try {
            $query = "DELETE FROM UTILISATEUR WHERE idU = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Modifie les données d'un utilisateur dans la base de données en fonction de son ID.
     *
     * @param int $userId L'ID de l'utilisateur à modifier.
     * @param array $newUserData Les nouvelles données de l'utilisateur.
     * @return bool True si la modification est réussie, False sinon.
     */
    public function modifierUtilisateur(int $userId, array $newUserData) {
        try {
            $query = "UPDATE UTILISATEUR SET isAdmin = ?, pseudo = ?, mdp = ?, adresseMail = ? WHERE idU = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$newUserData['isAdmin'], $newUserData['pseudo'], $newUserData['mdp'], $newUserData['adresseMail'], $userId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère tous les utilisateurs de la base de données.
     *
     * @return array Un tableau contenant tous les utilisateurs.
     */
    public function obtenirTousUtilisateurs() {
        $query = "SELECT * FROM UTILISATEUR";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un utilisateur en fonction de son ID.
     *
     * @param int $userId L'ID de l'utilisateur à récupérer.
     * @return array|false Les données de l'utilisateur ou False si l'utilisateur n'est pas trouvé.
     */
    public function obtenirUtilisateurParId(int $userId) {
        $query = "SELECT * FROM UTILISATEUR WHERE idU = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }
}


?>