<?php

declare(strict_types=1);

namespace Test\ModelsTest;

require_once __DIR__ . "/../../App/Autoloader/autoloader.php";

use App\Autoloader\Autoloader;
use App\Models\Genre;

Autoloader::register();

/**
 * Classe de test pour la classe Genre.
 */
class GenreTest {

    /**
     * Exécute tous les tests de la classe Genre.
     */
    public function runTests() {
        $this->testConstruct();
        $this->testGetters();
        $this->testEquals();
    }

    /**
     * Teste la construction d'un genre.
     */
    public function testConstruct() {
        $genre = new Genre(1, "Rock");

        if ($genre instanceof Genre) {
            echo "<< [Test de construction : Success]\n";
        } else {
            echo "<< [Test de construction : Fail]\n";
        }
    }

    /**
     * Teste les accesseurs du genre.
     */
    public function testGetters() {
        $genre = new Genre(1, "Rock");

        if ($genre->getId() === 1 && $genre->getNomGenre() === "Rock") {
            echo "<< [Test d'accesseurs : Success]\n";
        } else {
            echo "<< [Test d'accesseurs : Fail]\n";
        }
    }

    /**
     * Teste l'égalité entre deux genres.
     */
    public function testEquals() {
        $genre1 = new Genre(1, "Rock");
        $genre2 = new Genre(1, "Rock");
        $genre3 = new Genre(2, "Pop");

        if ($genre1->equals($genre2) && !$genre1->equals($genre3)) {
            echo "<< [Test Equals : Success]\n";
        } else {
            echo "<< [Test Equals : Fail]\n";
        }
    }
}

// Exécution des tests
$genreTest = new GenreTest();
$genreTest->runTests();
?>
