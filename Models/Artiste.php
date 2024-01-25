<?php

class Artiste {
    private int $idA;

    private string $nomA;

    public function __construct($idA, $nomA) {
        $this->idA = $idA;
        $this->nomA = $nomA;
    }

    public function getId() {
        return $this->idA;
    }

    public function getNomGenre() {
        return $this->nomA;
    }
}

?>