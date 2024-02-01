<?php

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use PDO;
use PDOException;

Autoloader::register();

class CrudContenir
{
    private $db;

    /**
     * Constructeur de la classe CrudContenir.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $db La connexion à la base de données.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Ajoute une relation entre une playlist et un album dans la base de données.
     *
     * @param int $idPlaylist L'ID de la playlist.
     * @param int $idAlbum    L'ID de l'album.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterRelation(int $idPlaylist, int $idAlbum)
    {
        try {
            $query = "INSERT INTO CONTENIR (idPlaylist, idAl) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idPlaylist, $idAlbum]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime une relation entre une playlist et un album dans la base de données.
     *
     * @param int $idPlaylist L'ID de la playlist.
     * @param int $idAlbum    L'ID de l'album.
     * @return bool True si la suppression est réussie, False sinon.
     */
    public function supprimerRelation(int $idPlaylist, int $idAlbum)
    {
        try {
            $query = "DELETE FROM CONTENIR WHERE idPlaylist = ? AND idAl = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idPlaylist, $idAlbum]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère tous les albums associés à une playlist en fonction de l'ID de la playlist.
     *
     * @param int $idPlaylist L'ID de la playlist.
     * @return array Un tableau contenant tous les albums associés à la playlist.
     */
    public function obtenirTousAlbumsPourPlaylist(int $idPlaylist)
    {
        $query = "SELECT * FROM CONTENIR WHERE idPlaylist = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idPlaylist]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les playlists associées à un album en fonction de l'ID de l'album.
     *
     * @param int $idAlbum L'ID de l'album.
     * @return array Un tableau contenant toutes les playlists associées à l'album.
     */
    public function obtenirToutesPlaylistsPourAlbum(int $idAlbum)
    {
        $query = "SELECT * FROM CONTENIR WHERE idAl = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idAlbum]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
