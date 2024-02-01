<?php

namespace App\Models;

require_once __DIR__ . '/../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \App\Models\Artiste;
use \App\Models\Genre;
use DateTime;

Autoloader::register();

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
    private int $idAl;

    /**
     * @var string L'identifiant de l'image associée à la musique.
     */
    private string $img;

    /**
     * @var DateTime La date de sortie de la musique.
     */
    private int $dateDeSortie;

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
     * @param int      $idAl           L'identifiant unique de la musique.
     * @param int      $img         L'identifiant de l'image associée à la musique.
     * @param int $dateDeSortie  La date de sortie de la musique.
     * @param string   $title         Le titre de la musique.
     * @param array    $compositeur   La liste de compositeurs de la musique.
     * @param array    $interprete    La liste d'interprète de la musique.
     * @param array    $genre         La liste de genre de l'album.
     */
    public function __construct($idAl, $img, $dateDeSortie, $title, $compositeur, $interprete, $genre) {
        $this->idAl = $idAl;
        $this->img = $img;
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
        return $this->idAl;
    }

    /**
     * Obtenir l'identifiant de l'image associée à la musique.
     *
     * @return int L'identifiant de l'image.
     */
    public function getImg() {
        return $this->img;
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
     * @param int $img Le nouvel identifiant de l'image.
     */
    public function setImg(int $img) {
        $this->img = $img;
    }

    /**
     * Définir la date de sortie de la musique.
     *
     * @param DateTime $newDate La nouvelle date de sortie.
     */
    public function setDateSortie(int $newDate) {
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

    /**
     * Vérifier l'égalité avec un autre album.
     *
     * @param Album $other L'autre album à comparer.
     *
     * @return bool Retourne true si les albums sont égaux, sinon false.
     */
    public function equals(Album $other)
    {
        return $this->idAl === $other->idAl;
    }
}

?>
