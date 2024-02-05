<?php

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \App\Models\User;
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
     * Ajoute un nouvel utilisateur à la base de données depuis une donnée yml.
     *
     * @param array $userData Les données de l'utilisateur à ajouter.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterUtilisateurFromYml(array $userData) {
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
     * Ajoute un nouvel utilisateur à la base de données depuis une donnée objet.
     *
     * @param User $userData Les données de l'utilisateur à ajouter.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterUtilisateurFromObject(User $userData) {
        try {
            $query = "INSERT INTO UTILISATEUR (isAdmin, pseudo, mdp, adresseMail) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([0,
                            $userData->getPseudo(), 
                            $userData->getMdp(), 
                            $userData->getMail()]);
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
     * @param User $newUserData Les nouvelles données de l'utilisateur.
     * @return bool True si la modification est réussie, False sinon.
     */
    public function modifierUtilisateur(int $userId, User $newUserData) {
        try {
            if ($newUserData->isAdmin()) {
                $query = "UPDATE UTILISATEUR SET isAdmin = 1, pseudo = ?, mdp = ?, adresseMail = ? WHERE idU = ?";   
            }
            else {
                $query = "UPDATE UTILISATEUR SET isAdmin = 0, pseudo = ?, mdp = ?, adresseMail = ? WHERE idU = ?";
            }
            $stmt = $this->db->prepare($query);
            $stmt->execute([$newUserData->getPseudo(), 
                            $newUserData->getMdp(), 
                            $newUserData->getMail(), 
                            $userId]);
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

    /**
     * Récupère un utilisateur en fonction de son pseudo.
     *
     * @param string $pseudo Le pseudo de l'utilisateur à récupérer.
     * @return array|false Les données de l'utilisateur ou False si l'utilisateur n'est pas trouvé.
     */
    public function obtenirUtilisateurParPseudo(string $pseudo) {
        $query = "SELECT * FROM UTILISATEUR WHERE pseudo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$pseudo]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }

    public function obtenirPlaylistsUtilisateur(int $idUser) {
        $query = "SELECT * FROM PLAYLIST WHERE idPlaylist IN (SELECT idPlaylist FROM POSSEDER WHERE idU = ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idUser]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un utilisateur en fonction de son pseudo et mdp.
     *
     * @param string $pseudo Le pseudo de l'utilisateur à récupérer.
     * @param string $mdp    Le mot de passe de l'utilisateur à récupérer.
     * @return true|false   Les données de l'utilisateur ou False si l'utilisateur n'est pas trouvé.
     */
    public function isAuth(string $pseudo, string $mdp) {
        $query = "SELECT * FROM UTILISATEUR WHERE pseudo = ? and mdp = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$pseudo, $mdp]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
}


?>