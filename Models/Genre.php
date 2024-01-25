<?php

class Genre {
    private int $idG;

    private string $nomG;

    public function __construct($idG, $nomG) {
        $this->idG = $idG;
        $this->nomG = $nomG;
    }

    public function getId() {
        return $this->idG;
    }

    public function getNomGenre() {
        return $this->nomG;
    }
}

?>