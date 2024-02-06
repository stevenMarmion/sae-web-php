<?php

declare(strict_types=1);

namespace App\Models;

require_once __DIR__ . '/../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \App\Models\Album;

Autoloader::register();

/**
 * Class User
 *
 * Représente un utilisateur avec des informations de base et des éléments favoris.
 */
class User {
    /**
     * @var int L'identifiant unique de l'utilisateur.
     */
    private int $idU;

    /**
     * @var string Le nom d'utilisateur choisi par l'utilisateur.
     */
    private string $pseudo;

    /**
     * @var string Le mot de passe hashé de l'utilisateur.
     */
    private string $motDePasse;

    /**
     * @var string L'adresse e-mail de l'utilisateur.
     */
    private string $adresseMail;

    /**
     * @var bool Le rôle de l'utilisateur, indiquant s'il s'agit d'un administrateur.
     */
    private bool $isAdmin;

    /**
     * @var array Un tableau contenant les éléments favoris de l'utilisateur.
     */
    private array $favoris;

    /**
     * Constructeur de la classe User.
     *
     * @param int    $idU         L'identifiant unique de l'utilisateur.
     * @param string $pseudo      Le nom d'utilisateur choisi par l'utilisateur.
     * @param string $motDePasse  Le mot de passe hashé de l'utilisateur.
     * @param string $adresseMail L'adresse e-mail de l'utilisateur.
     * @param bool   $isAdmin     Le rôle de l'utilisateur, indiquant s'il s'agit d'un administrateurn.
     * @param array  $favoris     Un tableau contenant les éléments favoris de l'utilisateur.
     */
    public function __construct($idU, $pseudo, $motDePasse, $adresseMail, $isAdmin, $favoris) {
        $this->idU = $idU;
        $this->pseudo = $pseudo;
        $this->motDePasse = $motDePasse;
        $this->adresseMail = $adresseMail;
        $this->isAdmin = $isAdmin;
        $this->favoris = $favoris;
    }

    /**
     * Obtenir l'identifiant unique de l'utilisateur.
     *
     * @return int L'identifiant unique.
     */
    public function getId() {
        return $this->idU;
    }

    /**
     * Obtenir le nom d'utilisateur choisi par l'utilisateur.
     *
     * @return string Le nom d'utilisateur.
     */
    public function getPseudo() {
        return $this->pseudo;
    }

    /**
     * Obtenir le mot de passe utilisateur choisi par l'utilisateur.
     *
     * @return string Le mot de passe de l'utilisateur.
     */
    public function getMdp() {
        return $this->motDePasse;
    }

    /**
     * Obtenir le rôle de l'utilisateur.
     *
     * @return bool Le rôle de l'utilisateur.
     */
    public function isAdmin() {
        return $this->isAdmin;
    }

    /**
     * Obtenir l'adresse e-mail de l'utilisateur.
     *
     * @return string L'adresse e-mail.
     */
    public function getMail() {
        return $this->adresseMail;
    }

    /**
     * Obtenir les éléments favoris de l'utilisateur.
     *
     * @return array Un tableau contenant les éléments favoris de l'utilisateur.
     */
    public function getFavoris() {
        return $this->favoris;
    }

    /**
     * Définir le nom d'utilisateur de l'utilisateur.
     *
     * @param string $p Le nouveau nom d'utilisateur.
     */
    public function setPseudo(string $p) {
        $this->pseudo = $p;
    }

    /**
     * Définir l'adresse e-mail de l'utilisateur.
     *
     * @param string $mail La nouvelle adresse e-mail.
     */
    public function setMail(string $mail) {
        $this->adresseMail = $mail;
    }

    /**
     * Définir les éléments favoris de l'utilisateur.
     *
     * @param array $favoris Le nouveau tableau d'éléments favoris.
     */
    public function setFavoris(array $favoris) {
        $this->favoris = $favoris;
    }

    /**
     * Supprimer un élément favori de l'utilisateur.
     *
     * @param int $idFavori L'identifiant de l'élément favori à supprimer.
     *
     * @return bool Retourne true si l'élément a été supprimé avec succès, sinon false.
     */
    public function supprimeFavori(int $idFavori) {
        foreach ($this->getFavoris() as $index => $favori) {
            if ($favori->getId() === $idFavori) {
                unset($this->getFavoris()[$index]);
                return true;
            }
        }
        return false;
    }

    /**
     * Ajouter un élément favori à la liste de l'utilisateur.
     *
     * @param Album $favori L'objet Album à ajouter aux favoris.
     */
    public function ajouterFavori(Album $favori) {
        array_push($favoris, $favori);
    }

    /**
     * Vérifier l'égalité avec un autre utilisateur.
     *
     * @param User $other L'autre utilisateur à comparer.
     *
     * @return bool Retourne true si les utilisateurs sont égaux, sinon false.
     */
    public function equals(User $other)
    {
        return $this->idU === $other->idU && $this->pseudo === $other->pseudo && $this->motDePasse === $other->motDePasse
            && $this->adresseMail === $other->adresseMail && $this->isAdmin === $other->isAdmin && $this->favoris === $other->favoris;
    }

}

?>
