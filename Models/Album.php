<?php

use DateTime;

/**
 * Class Musique
 *
 * Représente une musique avec des informations telles que l'identifiant, la date de sortie, le titre,
 * les artistes compositeur et interprète, le genre, etc.
 */
class Album {

    /**
     * @var int L'identifiant unique de la musique.
     */
    private int $idM;

    /**
     * @var int L'identifiant de l'image associée à la musique.
     */
    private int $idImg;

    /**
     * @var DateTime La date de sortie de la musique.
     */
    private DateTime $dateDeSortie;

    /**
     * @var string Le titre de la musique.
     */
    private string $title;

    /**
     * @var array La liste de compositeurs de la musique.
     */
    private array $compositeur;

    /**
     * @var array La liste d'interprète de la musique.
     */
    private array $interprete;

    /**
     * @var array La liste de genre de l'album.
     */
    private array $genre;

    /**
     * Constructeur de la classe Musique.
     *
     * @param int      $idM           L'identifiant unique de la musique.
     * @param int      $idImg         L'identifiant de l'image associée à la musique.
     * @param DateTime $dateDeSortie  La date de sortie de la musique.
     * @param string   $title         Le titre de la musique.
     * @param array    $compositeur   La liste de compositeurs de la musique.
     * @param array    $interprete    La liste d'interprète de la musique.
     * @param array    $genre         La liste de genre de l'album.
     */
    public function __construct($idM, $idImg, $dateDeSortie, $title, $compositeur, $interprete, $genre) {
        $this->idM = $idM;
        $this->idImg = $idImg;
        $this->dateDeSortie = $dateDeSortie;
        $this->title = $title;
        $this->compositeur = $compositeur;
        $this->interprete = $interprete;
        $this->genre = $genre;
    }

    /**
     * Obtenir l'identifiant unique de la musique.
     *
     * @return int L'identifiant unique.
     */
    public function getId() {
        return $this->idM;
    }

    /**
     * Obtenir l'identifiant de l'image associée à la musique.
     *
     * @return int L'identifiant de l'image.
     */
    public function getIdImg() {
        return $this->idImg;
    }

    /**
     * Obtenir la date de sortie de la musique.
     *
     * @return DateTime La date de sortie.
     */
    public function getDateSortie() {
        return $this->dateDeSortie;
    }

    /**
     * Obtenir le titre de la musique.
     *
     * @return string Le titre de la musique.
     */
    public function getTitre() {
        return $this->title;
    }

    /**
     * Obtenir la liste des compositeurs de la musique.
     *
     * @return array La liste des compositeurs.
     */
    public function getCompositeur() {
        return $this->compositeur;
    }

    /**
     * Obtenir la liste d'interprètes de l'album.
     *
     * @return array La liste d'interprètes.
     */
    public function getInterprete() {
        return $this->interprete;
    }

    /**
     * Obtenir le liste de genre de l'album.
     *
     * @return array La liste de genre de l'album.
     */
    public function getGenre() {
        return $this->genre;
    }

    /**
     * Définir l'identifiant de l'image associée à la musique.
     *
     * @param int $idImg Le nouvel identifiant de l'image.
     */
    public function setIdImg(int $idImg) {
        $this->idImg = $idImg;
    }

    /**
     * Définir la date de sortie de la musique.
     *
     * @param DateTime $newDate La nouvelle date de sortie.
     */
    public function setDateSortie(DateTime $newDate) {
        $this->dateDeSortie = $newDate;
    }

    /**
     * Définir le titre de la musique.
     *
     * @param string $title Le nouveau titre de la musique.
     */
    public function setTitre(string $title) {
        $this->title = $title;
    }

    /**
     * Définir l'artiste compositeur de la musique.
     *
     * @param Artiste $newComp Le nouvel artiste compositeur.
     */
    public function setCompositeur(Artiste $newComp) {
        $this->compositeur = $newComp;
    }

    /**
     * Définir l'artiste interprète de la musique.
     *
     * @param Artiste $newInt Le nouvel artiste interprète.
     */
    public function setInterprete(Artiste $newInt) {
        $this->interprete = $newInt;
    }

    /**
     * Définir le genre de la musique.
     *
     * @param Genre $newGenre Le nouveau genre de la musique.
     */
    public function setGenre(Genre $newGenre) {
        $this->genre = $newGenre;
    }
}

?>
