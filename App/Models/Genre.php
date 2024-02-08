<?php

declare(strict_types=1);

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

    /**
     * Vérifier l'égalité avec un autre genre musical.
     *
     * @param Genre $other L'autre genre musical à comparer.
     *
     * @return bool Retourne true si les genres musicaux sont égaux, sinon false.
     */
    public function equals(Genre $other)
    {
        return $this->idG == $other->idG && $this->nomG == $other->nomG;
    }

}

?>
