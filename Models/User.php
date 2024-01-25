<?php

class User {
    private int $idU;

    private string $pseudo;

    private string $motDePasse;

    private string $adresseMail;
    
    private string $isAdmin;

    private array $favoris;

    public function __construct($idU, $pseudo, $motDePasse, $adresseMail, $isAdmin, $favoris) {
        $this->idU = $idU;
        $this->pseudo = $pseudo;
        $this->motDePasse = $motDePasse;
        $this->adresseMail = $adresseMail;
        $this->isAdmin = $isAdmin;
        $this->favoris = $favoris;
    }

    public function getId() {
        return $this->idU;
    }

    public function getPseudo() {
        return $this->pseudo;
    }

    public function getMail() {
        return $this->adresseMail;
    }

    public function getFavoris() {
        return $this->favoris;
    }

    public function setPseudo(string $p) {
        $this->pseudo = $p;
    }

    public function setMail(string $mail) {
        $this->adresseMail = $mail;
    }

    public function setFavoris(array $favoris) {
        $this->favoris = $favoris;
    }

    public function supprimeFavori(int $idFavori) {
        foreach ($this->getFavoris() as $index => $favori) {
            if ($favori->getId() === $idFavori) {
                unset($this->getFavoris()[$index]);
                return true;
            }
        }
        return false;
    }

    public function ajouterFavori(Musique $favori) {
        array_push($favoris, $favori);
    }

}

?>