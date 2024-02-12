<?php

declare(strict_types=1);

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use PDO;
use PDOException;

Autoloader::register();

class CrudNote
{
    private $db;

    /**
     * Constructeur de la classe CrudNote.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $db La connexion à la base de données.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Ajoute une nouvelle note à la base de données.
     *
     * @param int   $idAlbum L'ID de l'album associé à la note.
     * @param int   $idUser  L'ID de l'utilisateur qui attribue la note.
     * @param float $note    La note attribuée (entre 0 et 5).
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterNote(int $idAlbum, int $idUser, float $note)
    {
        try {
            $query = "INSERT INTO NOTE (idAl, idU, note) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idAlbum, $idUser, $note]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime une note de la base de données en fonction de l'ID de l'album et de l'ID de l'utilisateur.
     *
     * @param int $idAlbum L'ID de l'album associé à la note.
     * @param int $idUser  L'ID de l'utilisateur qui a attribué la note.
     * @return bool True si la suppression est réussie, False sinon.
     */
    public function supprimerNote(int $idAlbum, int $idUser)
    {
        try {
            $query = "DELETE FROM NOTE WHERE idAl = ? AND idU = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idAlbum, $idUser]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function supprimerToutesNotesFromIdU(int $idUser)
    {
        try {
            $query = "DELETE FROM NOTE WHERE idU = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idUser]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }


    public function supprimerToutesNotesFromIdAlbum(int $idAlbum)
    {
        try {
            $query = "DELETE FROM NOTE WHERE idAl = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idAlbum]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Modifie la note d'un album dans la base de données en fonction de l'ID de l'album et de l'ID de l'utilisateur.
     *
     * @param int   $idAlbum     L'ID de l'album associé à la note.
     * @param int   $idUser      L'ID de l'utilisateur qui a attribué la note.
     * @param float $nouvelleNote La nouvelle note attribuée (entre 0 et 5).
     * @return bool True si la modification est réussie, False sinon.
     */
    public function modifierNote(int $idAlbum, int $idUser, float $nouvelleNote)
    {
        try {
            $query = "UPDATE NOTE SET note = ? WHERE idAl = ? AND idU = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$nouvelleNote, $idAlbum, $idUser]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère toutes les notes associées à un album en fonction de son ID.
     *
     * @param int $idAlbum L'ID de l'album.
     * @return array|false Un tableau contenant toutes les notes associées à l'album.
     */
    public function obtenirToutesNotesAlbum(int $idAlbum)
    {
        $query = "SELECT * FROM NOTE WHERE idAl = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idAlbum]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;
    }

    /**
     * Récupère la note d'un utilisateur pour un album en fonction de l'ID de l'album et de l'ID de l'utilisateur.
     *
     * @param int $idAlbum L'ID de l'album.
     * @param int $idUser  L'ID de l'utilisateur.
     * @return array|false Les données de la note ou False si la note n'est pas trouvée.
     */
    public function obtenirNoteUtilisateurPourAlbum(int $idAlbum, int $idUser)
    {
        $query = "SELECT * FROM NOTE WHERE idAl = ? AND idU = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idAlbum, $idUser]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }
}

?>
