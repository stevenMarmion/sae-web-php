<?php

namespace App\Models;

/**
 * Class Artiste
 *
 * Représente un artiste musical avec un identifiant unique et un nom.
 */
class Artiste {
    /**
     * @var int L'identifiant unique de l'artiste musical.
     */
    private int $idA;

    /**
     * @var string Le nom de l'artiste musical.
     */
    private string $nomA;

    /**
     * Constructeur de la classe Artiste.
     *
     * @param int    $idA  L'identifiant unique de l'artiste musical.
     * @param string $nomA Le nom de l'artiste musical.
     */
    public function __construct($idA, $nomA) {
        $this->idA = $idA;
        $this->nomA = $nomA;
    }

    /**
     * Obtenir l'identifiant unique de l'artiste musical.
     *
     * @return int L'identifiant unique.
     */
    public function getId() {
        return $this->idA;
    }

    /**
     * Obtenir le nom de l'artiste musical.
     *
     * @return string Le nom de l'artiste musical.
     */
    public function getNomArtiste() {
        return $this->nomA;
    }
}

?>
