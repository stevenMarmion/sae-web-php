<?php

namespace App\Models;

/**
 * Class Favori
 *
 * Représente un favori avec un identifiant unique d'utilisateur (idU) et un identifiant unique d'album (idAl).
 */
class Favori {
    /**
     * @var int L'identifiant unique de l'utilisateur.
     */
    private int $idUtilisateur;

    /**
     * @var int L'identifiant unique de l'album.
     */
    private int $idAlbum;

    /**
     * Constructeur de la classe Favori.
     *
     * @param int $idUtilisateur L'identifiant unique de l'utilisateur.
     * @param int $idAlbum       L'identifiant unique de l'album.
     */
    public function __construct(int $idUtilisateur, int $idAlbum) {
        $this->idUtilisateur = $idUtilisateur;
        $this->idAlbum = $idAlbum;
    }

    /**
     * Obtenir l'identifiant unique de l'utilisateur.
     *
     * @return int L'identifiant unique de l'utilisateur.
     */
    public function getIdUtilisateur(): int {
        return $this->idUtilisateur;
    }

    /**
     * Obtenir l'identifiant unique de l'album.
     *
     * @return int L'identifiant unique de l'album.
     */
    public function getIdAlbum(): int {
        return $this->idAlbum;
    }
}

?>
