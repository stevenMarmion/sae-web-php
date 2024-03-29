<?php

declare(strict_types=1);

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \App\Models\EntityOperations\CrudPosseder;
use App\Models\Playlist;
use PDO;
use PDOException;

Autoloader::register();

class CrudPlaylist
{
    private $db;

    private $crudPosseder;

    /**
     * Constructeur de la classe CrudPlaylist.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $db La connexion à la base de données.
     */
    public function __construct(PDO $db){
        $this->db = $db;
        $this->crudPosseder = new CrudPosseder($this->db);
    }

    /**
     * Ajoute une nouvelle playlist à la base de données.
     *
     * @param int    $idCreateur      L'ID de l'utilisateur associé à la playlist.
     * @param string $nomPlaylist Le nom de la playlist.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterPlaylist(int $idCreateur, string $nomPlaylist, string $imgPlaylist = "imageBase.jpg")
    {
        $query = "SELECT * FROM PLAYLIST WHERE idCreateur = ? and nomPlaylist = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idCreateur, $nomPlaylist]);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($row) > 0){
            return false; // l'utilisateur a deja une playlist avec ce nom
        }
        try {
            $query = "INSERT INTO PLAYLIST (idCreateur, nomPlaylist, imgPlaylist) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idCreateur, $nomPlaylist, $imgPlaylist]);
            $this->crudPosseder->ajouterRelation($idCreateur, intval($this->db->lastInsertId()));
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
    public function supprimerPlaylist(int $idUser, int $playlistId)
    {
        try {
            $query = "DELETE FROM PLAYLIST WHERE idPlaylist = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$playlistId]);
            $this->crudPosseder->supprimerRelation($idUser, $playlistId);
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

    public function modifierPlaylist(int $playlistId, int $idCreateur,Playlist $newPlaylistsDatas)
    {
        try {
            $query = "UPDATE PLAYLIST SET nomPlaylist = ?, imgPlaylist = ? WHERE idPlaylist = ? and idCreateur = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$newPlaylistsDatas->getNomPlaylist(), 
                            $newPlaylistsDatas->getImg(), 
                            $newPlaylistsDatas->getIdPlaylist(),
                            $idCreateur]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function modifierPlaylistAvecVerfiDupliNom(int $playlistId, int $idCreateur,Playlist $newPlaylistsDatas)
    {
        $query = "SELECT * FROM PLAYLIST WHERE idCreateur = ? AND nomPlaylist = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idCreateur, $newPlaylistsDatas->getNomPlaylist()]);
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;
        if (is_array($response) && count($response) > 0) {
            return false;
        }

        else if ($stmt != false ) {
            try {
                $query = "UPDATE PLAYLIST SET nomPlaylist = ?, imgPlaylist = ? WHERE idPlaylist = ? and idCreateur = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$newPlaylistsDatas->getNomPlaylist(), 
                                $newPlaylistsDatas->getImg(), 
                                $newPlaylistsDatas->getIdPlaylist(),
                                $idCreateur]);
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }
    }

    /**
     * Récupère toutes les playlists d'un utilisateur en fonction de son ID.
     *
     * @param int $idCreateur L'ID de l'utilisateur.
     * @return array Un tableau contenant toutes les playlists de l'utilisateur.
     */
    public function obtenirToutesPlaylistsIdU(int $idCreateur)
    {
        $query = "SELECT * FROM PLAYLIST WHERE idCreateur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idCreateur]);
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

    public function ajouterAlbumPlaylist(int $idPlaylist, int $idAlbum)
    {
        try {
            $query = "INSERT INTO CONTENIR (idPlaylist, idAl) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idPlaylist, $idAlbum]);
            return true;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function obtenirPlaylistSansIdAlbum(int $idAlbum, int $idU)
    {
        $query = "SELECT * FROM PLAYLIST natural join POSSEDER WHERE idPlaylist NOT IN (SELECT idPlaylist FROM CONTENIR WHERE idAl = ?) and idU = ? and nomPlaylist<>'Like'";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idAlbum,$idU]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function PlaylistFavoris(int $idU)
    {
        $query = "SELECT * FROM PLAYLIST WHERE idCreateur = ? and nomPlaylist = 'Like'";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idU]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: array();
    }

    public function supprimerAlbumPlaylist(int $idPlaylist, int $idAlbum)
    {
        try {
            $query = "DELETE FROM CONTENIR WHERE idPlaylist = ? and idAl = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idPlaylist, $idAlbum]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

?>
