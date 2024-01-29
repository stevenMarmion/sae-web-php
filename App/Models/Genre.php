<?php

namespace App\Models;

/**
 * Class Genre
 *
 * Représente un genre musical avec un identifiant unique et un nom.
 */
class Genre {
    /**
     * @var int L'identifiant unique du genre musical.
     */
    private int $idG;

    /**
     * @var string Le nom du genre musical.
     */
    private string $nomG;

    /**
     * Constructeur de la classe Genre.
     *
     * @param int    $idG  L'identifiant unique du genre musical.
     * @param string $nomG Le nom du genre musical.
     */
    public function __construct($idG, $nomG) {
        $this->idG = $idG;
        $this->nomG = $nomG;
    }

    /**
     * Obtenir l'identifiant unique du genre musical.
     *
     * @return int L'identifiant unique.
     */
    public function getId() {
        return $this->idG;
    }

    /**
     * Obtenir le nom du genre musical.
     *
     * @return string Le nom du genre musical.
     */
    public function getNomGenre() {
        return $this->nomG;
    }
}

?>
