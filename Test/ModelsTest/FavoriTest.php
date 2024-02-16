<?php

declare(strict_types=1);

namespace Test\ModelsTest;

require_once __DIR__ . "/../../App/Autoloader/autoloader.php";

use App\Autoloader\Autoloader;
use App\Models\Favori;

Autoloader::register();

/**
 * Classe de test pour la classe Favori.
 */
class FavoriTest {

    /**
     * Exécute tous les tests de la classe Favori.
     */
    public function runTests() {
        $this->testConstruct();
        $this->testGetters();
    }

    /**
     * Teste la construction d'un favori.
     */
    public function testConstruct() {
        $favori = new Favori(1, 2);

        if ($favori instanceof Favori) {
            echo "<< [Test Construct : Success]\n";
        } else {
            echo "<< [Test Construct : Fail]\n";
        }
    }

    /**
     * Teste les accesseurs du favori.
     */
    public function testGetters() {
        $favori = new Favori(1, 2);

        if ($favori->getIdUtilisateur() === 1 && $favori->getIdAlbum() === 2) {
            echo "<< [Test d'accesseurs : Success]\n";
        } else {
            echo "<< [Test d'accesseurs : Fail]\n";
        }
    }
}

// Exécution des tests
$favoriTest = new FavoriTest();
$favoriTest->runTests();
?>
