<?php

use DateTime;

class Musique {

    private int $idM;

    private int $idImg;

    private DateTime $dateDeSortie;

    private string $title;

    private Artiste $compositeur;

    private Artiste $interprete;

    private Genre $genre;

    public function __construct($idM, $idImg, $dateDeSortie, $title, $compositeur, $interprete, $genre) {
        $this->idM = $idM;
        $this->idImg = $idImg;
        $this->dateDeSortie = $dateDeSortie;
        $this->title = $title;
        $this->compositeur = $compositeur;
        $this->interprete = $interprete;
        $this->genre = $genre;
    }

    public function getId() {
        return $this->idM;
    }

    public function getIdImg() {
        return $this->idImg;
    }

    public function getDateSortie() {
        return $this->dateDeSortie;
    }

    public function getTtitre() {
        return $this->title;
    }

    public function getCompositeur() {
        return $this->compositeur;
    }

    public function getInterprete() {
        return $this->interprete;
    }

    public function getGenre() {
        return $this->genre;
    }

    public function setIdImg(int $idImg) {
        $this->idImg = $idImg;
    }

    public function setDateSortie(DateTime $newDate) {
        $this->dateDeSortie = $newDate;
    }

    public function setTtitle(string $title) {
        $this->title = $title;
    }

    public function setCompositeur(Artiste $newComp) {
        $this->compositeur = $newComp;
    }

    public function setInterprete(Artiste $newInt) {
        $this->interprete = $newInt;
    }

    public function setGenre(Genre $newGenre) {
        $this->genre = $newGenre;
    }

}

?>