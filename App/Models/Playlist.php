<?php

namespace App\Models;

require_once __DIR__ . '/../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \App\Models\Album;

Autoloader::register();

/**
 * Class Playlist
 *
 * Représente une playlist avec des informations de base et les albums qui y sont associés.
 */
class Playlist
{
    /**
     * @var int L'identifiant unique de la playlist.
     */
    private int $idPlaylist;

    /**
     * @var int L'identifiant de l'utilisateur auquel la playlist est associée.
     */
    private int $idU;

    /**
     * @var string Le nom de la playlist.
     */
    private string $nomPlaylist;

    /**
     * @var array La liste des albums dans la playlist.
     */
    private array $albums;

    /**
     * Constructeur de la classe Playlist.
     *
     * @param int    $idPlaylist L'identifiant unique de la playlist.
     * @param int    $idU        L'identifiant de l'utilisateur auquel la playlist est associée.
     * @param string $nomPlaylist Le nom de la playlist.
     */
    public function __construct($idPlaylist, $idU, $nomPlaylist)
    {
        $this->idPlaylist = $idPlaylist;
        $this->idU = $idU;
        $this->nomPlaylist = $nomPlaylist;
        $this->albums = [];
    }

    /**
     * Obtenir l'identifiant unique de la playlist.
     *
     * @return int L'identifiant unique.
     */
    public function getIdPlaylist()
    {
        return $this->idPlaylist;
    }

    /**
     * Obtenir l'identifiant de l'utilisateur associé à la playlist.
     *
     * @return int L'identifiant de l'utilisateur.
     */
    public function getIdU()
    {
        return $this->idU;
    }

    /**
     * Obtenir le nom de la playlist.
     *
     * @return string Le nom de la playlist.
     */
    public function getNomPlaylist()
    {
        return $this->nomPlaylist;
    }

    /**
     * Obtenir la liste des albums dans la playlist.
     *
     * @return array La liste des albums.
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * Ajouter un album à la playlist.
     *
     * @param Album $album L'objet Album à ajouter à la playlist.
     */
    public function ajouterAlbum(Album $album)
    {
        $this->albums[] = $album;
    }

    /**
     * Supprimer un album de la playlist.
     *
     * @param int $idAlbum L'identifiant de l'album à supprimer.
     *
     * @return bool Retourne true si l'album a été supprimé avec succès, sinon false.
     */
    public function supprimerAlbum(int $idAlbum)
    {
        foreach ($this->getAlbums() as $index => $album) {
            if ($album->getId() === $idAlbum) {
                unset($this->getAlbums()[$index]);
                return true;
            }
        }
        return false;
    }

    /**
     * Vérifier l'égalité avec une autre playlist.
     *
     * @param Playlist $other L'autre playlist à comparer.
     *
     * @return bool Retourne true si les playlists sont égales, sinon false.
     */
    public function equals(Playlist $other)
    {
        return $this->idPlaylist === $other->idPlaylist && $this->idU === $other->idU;
    }

}

?>
