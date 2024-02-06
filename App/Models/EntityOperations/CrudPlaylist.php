<?php

declare(strict_types=1);

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use PDO;
use PDOException;

Autoloader::register();

class CrudPlaylist
{
    private $db;

    /**
     * Constructeur de la classe CrudPlaylist.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $db La connexion à la base de données.
     */
    public function __construct(PDO $db){
        $this->db = $db;
    }

    /**
     * Ajoute une nouvelle playlist à la base de données.
     *
     * @param int    $idUser      L'ID de l'utilisateur associé à la playlist.
     * @param string $nomPlaylist Le nom de la playlist.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterPlaylist(int $idUser, string $nomPlaylist)
    {
        try {
            $query = "INSERT INTO PLAYLIST (idU, nomPlaylist) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idUser, $nomPlaylist]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime une playlist de la base de données en fonction de son ID.
     *
     * @param int $playlistId L'ID de la playlist à supprimer.
     * @return bool True si la suppression est réussie, False sinon.
     */
    public function supprimerPlaylist(int $playlistId)
    {
        try {
            $query = "DELETE FROM PLAYLIST WHERE idPlaylist = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$playlistId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function supprimerPlaylistByIdU(int $idU)
    {
        try {
            $query = "DELETE FROM PLAYLIST WHERE idU = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idU]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Modifie le nom d'une playlist dans la base de données en fonction de son ID.
     *
     * @param int    $playlistId   L'ID de la playlist à modifier.
     * @param string $newNomPlaylist Le nouveau nom de la playlist.
     * @return bool True si la modification est réussie, False sinon.
     */
    public function modifierNomPlaylist(int $playlistId, string $newNomPlaylist)
    {
        try {
            $query = "UPDATE PLAYLIST SET nomPlaylist = ? WHERE idPlaylist = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$newNomPlaylist, $playlistId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère toutes les playlists d'un utilisateur en fonction de son ID.
     *
     * @param int $idUser L'ID de l'utilisateur.
     * @return array Un tableau contenant toutes les playlists de l'utilisateur.
     */
    public function obtenirToutesPlaylistsUtilisateur(int $idUser)
    {
        $query = "SELECT * FROM PLAYLIST WHERE idU = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idUser]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une playlist en fonction de son ID.
     *
     * @param int $playlistId L'ID de la playlist à récupérer.
     * @return array|false Les données de la playlist ou False si la playlist n'est pas trouvée.
     */
    public function obtenirPlaylistParId(int $playlistId)
    {
        $query = "SELECT * FROM PLAYLIST WHERE idPlaylist = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$playlistId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }
}

?>
