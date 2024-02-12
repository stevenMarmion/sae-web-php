<?php

declare(strict_types=1);

namespace Test\ModelsTest;

require_once __DIR__ . "/../../App/Autoloader/autoloader.php";

use App\Autoloader\Autoloader;
use App\Models\Playlist;
use App\Models\Album;

Autoloader::register();

/**
 * Classe de test pour la classe Playlist.
 */
class PlaylistTest {

    /**
     * Exécute tous les tests de la classe Playlist.
     */
    public function runTests() {
        $this->testConstruct();
        $this->testGetters();
        $this->testAjouterAlbum();
        $this->testSupprimerAlbum();
        $this->testEquals();
    }

    /**
     * Teste la construction d'une playlist.
     */
    public function testConstruct() {
        $playlist = new Playlist(1, 2, "image.png", "Ma Playlist");

        if ($playlist instanceof Playlist) {
            echo "<< [Test de construction : Success]\n";
        } else {
            echo "<< [Test de construction : Fail]\n";
        }
    }

    /**
     * Teste les accesseurs de la playlist.
     */
    public function testGetters() {
        $playlist = new Playlist(1, 2, "image.png", "Ma Playlist");

        if ($playlist->getIdPlaylist() === 1 &&
            $playlist->getIdCreateur() === 2 &&
            $playlist->getImg() === "image.png" &&
            $playlist->getNomPlaylist() === "Ma Playlist" &&
            empty($playlist->getAlbums())) {
            echo "<< [Test d'accesseurs : Success]\n";
        } else {
            echo "<< [Test d'accesseurs : Fail]\n";
        }
    }

    /**
     * Teste l'ajout d'un album à la playlist.
     */
    public function testAjouterAlbum() {
        $album1 = new Album(1, "image.png", 2023, "Titre 1", [], [], []);
        $album2 = new Album(2, "image2.png", 2024, "Titre 2", [], [], []);

        $playlist = new Playlist(1, 2, "image.png", "Ma Playlist");
        $playlist->ajouterAlbum($album1);
        $playlist->ajouterAlbum($album2);

        if (count($playlist->getAlbums()) === 2 &&
            $playlist->getAlbums()[0] === $album1 &&
            $playlist->getAlbums()[1] === $album2) {
            echo "<< [Test Ajouter Album : Success]\n";
        } else {
            echo "<< [Test Ajouter Album : Fail]\n";
        }
    }

    /**
     * Teste la suppression d'un album de la playlist.
     */
    public function testSupprimerAlbum() {
        $album1 = new Album(1, "image.png", 2023, "Titre 1", [], [], []);
        $album2 = new Album(2, "image2.png", 2024, "Titre 2", [], [], []);

        $playlist = new Playlist(1, 2, "image.png", "Ma Playlist");
        $playlist->ajouterAlbum($album1);
        $playlist->ajouterAlbum($album2);

        $playlist->supprimerAlbum(2);

        if (count($playlist->getAlbums()) === 1 && $playlist->getAlbums()[0] === $album1) {
            echo "<< [Test Supprimer Album : Success]\n";
        } else {
            echo "<< [Test Supprimer Album : Fail]\n";
        }
    }

    /**
     * Teste l'égalité entre deux playlists.
     */
    public function testEquals() {
        $playlist1 = new Playlist(1, 2, "image.png", "Ma Playlist");
        $playlist2 = new Playlist(1, 2, "image.png", "Ma Playlist");
        $playlist3 = new Playlist(2, 2, "image2.png", "Autre Playlist");

        if ($playlist1->equals($playlist2) && !$playlist1->equals($playlist3)) {
            echo "<< [Test Equals : Success]\n";
        } else {
            echo "<< [Test Equals : Fail]\n";
        }
    }
}

// Exécution des tests
$playlistTest = new PlaylistTest();
$playlistTest->runTests();
?>
