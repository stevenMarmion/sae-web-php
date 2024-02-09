<?php

declare(strict_types=1);

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
    public function __construct(int $idA, string $nomA) {
        $this->idA = $idA;
        $this->nomA = $nomA;
    }

    /**
     * Obtenir l'identifiant unique de l'artiste musical.
     *
     * @return int L'identifiant unique.
     */
    public function getId(): int {
        return $this->idA;
    }

    /**
     * Obtenir le nom de l'artiste musical.
     *
     * @return string Le nom de l'artiste musical.
     */
    public function getNomArtiste(): string {
        return $this->nomA;
    }

    /**
     * Définir l'identifiant unique de l'artiste musical.
     *
     * @param int $idA L'identifiant unique à définir.
     */
    public function setId(int $idA): void {
        $this->idA = $idA;
    }

    /**
     * Définir le nom de l'artiste musical.
     *
     * @param string $nomA Le nom à définir.
     */
    public function setNomArtiste(string $nomA): void {
        $this->nomA = $nomA;
    }

    /**
     * Vérifier l'égalité avec un autre artiste.
     *
     * @param Artiste $other L'autre artiste à comparer.
     *
     * @return bool Retourne true si les artistes sont égaux, sinon false.
     */
    public function equals(Artiste $other): bool
    {
        return $this->idA === $other->idA;
    }
}

?>
