<?php

declare(strict_types=1);

namespace Test\ModelsTest;

require_once __DIR__ . "/../../App/Autoloader/autoloader.php";

use App\Autoloader\Autoloader;
use App\Models\Artiste;
use App\Models\Album;
use App\Models\Genre;

Autoloader::register();

/**
 * Classe de test pour la classe Album.
 */
class AlbumTest {

    /**
     * Exécute tous les tests de la classe Album.
     */
    public function runTests() {
        $this->testConstruct();
        $this->testGetters();
        $this->testSetters();
        $this->testAjouterCompositeur();
        $this->testAjouterInterprete();
        $this->testAjouterGenre();
        $this->testEquals();
    }

    /**
     * Teste la construction d'un album.
     */
    public function testConstruct() {
        $compositeur = new Artiste(1, "Compositeur");
        $interprete = new Artiste(1, "Interprète");
        $genre = new Genre(1, "Genre");

        $album = new Album(1, "image.png", 2023, "Titre", [$compositeur], [$interprete], [$genre]);

        if ($album instanceof Album) {
            echo "<< [Test de construction : Success]\n";
        } else {
            echo "<< [Test de construction : Fail]\n";
        }
    }

    /**
     * Teste les accesseurs de l'album.
     */
    public function testGetters() {
        $compositeur = new Artiste(1, "Compositeur");
        $interprete = new Artiste(1, "Interprète");
        $genre = new Genre(1, "Genre");

        $album = new Album(1, "image.png", 2023, "Titre", [$compositeur], [$interprete], [$genre]);

        if ($album->getId() === 1 &&
            $album->getImg() === "image.png" &&
            $album->getDateSortie() == 2023 &&
            $album->getTitre() === "Titre" &&
            $album->getCompositeurs() === [$compositeur] &&
            $album->getInterpretes() === [$interprete] &&
            $album->getGenres() === [$genre]) {
            echo "<< [Test d'accesseurs : Success]\n";
        } else {
            echo "<< [Test d'accesseurs : Fail]\n";
        }
    }

    /**
     * Teste les mutateurs de l'album.
     */
    public function testSetters() {
        $album = new Album(1, "image.png", 2023, "Titre", [], [], []);

        $album->setImg("image2.png");
        $album->setDateSortie(2002);
        $album->setTitre("Nouveau titre");

        if ($album->getImg() === "image2.png" &&
            $album->getDateSortie() == 2002 &&
            $album->getTitre() === "Nouveau titre") {
            echo "<< [Test de mutateurs : Success]\n";
        } else {
            echo "<< [Test de mutateurs : Fail]\n";
        }
    }

    /**
     * Teste l'ajout d'un compositeur à l'album.
     */
    public function testAjouterCompositeur() {
        $compositeur1 = new Artiste(1, "Nom 1");
        $compositeur2 = new Artiste(1, "Nom 2");

        $album = new Album(1, "image.png", 2023, "Titre", [], [], []);

        $album->ajouterCompositeur($compositeur1);
        $album->ajouterCompositeur($compositeur2);

        if ($album->getCompositeurs() === [$compositeur1, $compositeur2]) {
            echo "<< [Test Ajouter Compositeur: Success]\n";
        } else {
            echo "<< [Test Ajouter Compositeur: Fail]\n";
        }
    }

    /**
     * Teste l'ajout d'un interprète à l'album.
     */
    public function testAjouterInterprete() {
        $interprete1 = new Artiste(1, "Interprète 1");
        $interprete2 = new Artiste(1, "Interprète 2");

        $album = new Album(1, "image.png", 2023, "Titre", [], [], []);

        $album->ajouterInterprete($interprete1);
        $album->ajouterInterprete($interprete2);

        if ($album->getInterpretes() === [$interprete1, $interprete2]) {
            echo "<< [Test Ajouter Interprète: Success]\n";
        } else {
            echo "<< [Test Ajouter Interprète: Fail]\n";
        }
    }

    /**
     * Teste l'ajout d'un genre à l'album.
     */
    public function testAjouterGenre() {
        $genre1 = new Genre(1, "Genre 1");
        $genre2 = new Genre(2, "Genre 2");

        $album = new Album(1, "image.png", 2023, "Titre", [], [], []);

        $album->ajouterGenre($genre1);
        $album->ajouterGenre($genre2);

        if ($album->getGenres() === [$genre1, $genre2]) {
            echo "<< [Test Ajouter Genre: Success]\n";
        } else {
            echo "<< [Test Ajouter Genre: Fail]\n";
        }
    }

    /**
     * Teste l'égalité entre deux albums.
     */
    public function testEquals() {
        $album1 = new Album(1, "image.png", 2023, "Titre", [], [], []);
        $album2 = new Album(1, "image.png", 2023, "Titre", [], [], []);
        $album3 = new Album(2, "image2.png", 2024, "Titre 2", [], [], []);

        if ($album1->equals($album2) && !$album1->equals($album3)) {
            echo "<< [Test Equals: Success]\n";
        } else {
            echo "<< [Test Equals: Fail]\n";
        }
    }
}

// Exécution des tests
$albumTest = new AlbumTest();
$albumTest->runTests();
?>
