<?php

declare(strict_types=1);

namespace App\ModelsTest;

require_once __DIR__ . "/../../App/Autoloader/autoloader.php";

use App\Autoloader\Autoloader;
use App\Models\Artiste;

Autoloader::register();

/**
 * Classe de test pour la classe Artiste.
 */
class ArtisteTest {

    /**
     * Exécute tous les tests de la classe Artiste.
     */
    public function runTests() {
        $this->testConstruct();
        $this->testGetters();
        $this->testSetters();
        $this->testEquals();
    }

    /**
     * Teste la construction d'un artiste.
     */
    public function testConstruct() {
        $artiste = new Artiste(1, "Nom");

        if ($artiste instanceof Artiste) {
            echo "<< [Test de construction : Success]\n";
        } else {
            echo "<< [Test de construction : Fail]\n";
        }
    }

    /**
     * Teste les accesseurs de l'artiste.
     */
    public function testGetters() {
        $artiste = new Artiste(1, "Nom");

        if ($artiste->getId() === 1 && $artiste->getNomArtiste() === "Nom") {
            echo "<< [Test d'accesseurs : Success]\n";
        } else {
            echo "<< [Test d'accesseurs : Fail]\n";
        }
    }

    /**
     * Teste les mutateurs de l'artiste.
     */
    public function testSetters() {
        $artiste = new Artiste(1, "Nom");
        $artiste->setId(2);
        $artiste->setNomArtiste("Nouveau nom");

        if ($artiste->getId() === 2 && $artiste->getNomArtiste() === "Nouveau nom") {
            echo "<< [Test de mutateurs : Success]\n";
        } else {
            echo "<< [Test de mutateurs : Fail]\n";
        }
    }

    /**
     * Teste l'égalité entre deux artistes.
     */
    public function testEquals() {
        $artiste1 = new Artiste(1, "Nom");
        $artiste2 = new Artiste(1, "Nom");
        $artiste3 = new Artiste(2, "Autre nom");

        if ($artiste1->equals($artiste2) && !$artiste1->equals($artiste3)) {
            echo "<< [Test Equals : Success]\n";
        } else {
            echo "<< [Test Equals : Fail]\n";
        }
    }
}

// Exécution des tests
$artisteTest = new ArtisteTest();
$artisteTest->runTests();
?>
