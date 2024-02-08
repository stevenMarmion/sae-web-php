<?php

declare(strict_types=1);

namespace Test\ModelsTest;

require_once __DIR__ . "/../../App/Autoloader/autoloader.php";

use App\Autoloader\Autoloader;
use App\Models\User;
use App\Models\Album;

Autoloader::register();

/**
 * Classe de test pour la classe User.
 */
class UserTest {

    /**
     * Exécute tous les tests de la classe User.
     */
    public function runTests() {
        $this->testConstruct();
        $this->testGetters();
        $this->testSetters();
        $this->testAjouterFavori();
        $this->testSupprimerFavori();
        $this->testEquals();
    }

    /**
     * Teste la construction d'un utilisateur.
     */
    public function testConstruct() {
        $user = new User(1, "utilisateur", "motdepasse", "utilisateur@example.com", false, []);

        if ($user instanceof User) {
            echo "<< [Test de construction : Success]\n";
        } else {
            echo "<< [Test de construction : Fail]\n";
        }
    }

    /**
     * Teste les accesseurs de l'utilisateur.
     */
    public function testGetters() {
        $user = new User(1, "utilisateur", "motdepasse", "utilisateur@example.com", false, []);

        if ($user->getId() === 1 &&
            $user->getPseudo() === "utilisateur" &&
            $user->getMdp() === "motdepasse" &&
            $user->getMail() === "utilisateur@example.com" &&
            !$user->isAdmin() &&
            empty($user->getFavoris())) {
            echo "<< [Test d'accesseurs : Success]\n";
        } else {
            echo "<< [Test d'accesseurs : Fail]\n";
        }
    }

    /**
     * Teste les mutateurs de l'utilisateur.
     */
    public function testSetters() {
        $user = new User(1, "utilisateur", "motdepasse", "utilisateur@example.com", false, []);

        $user->setPseudo("nouveau_pseudo");
        $user->setMail("nouveau@mail.com");
        $user->setFavoris([new Album(1, "image.png", 2023, "Titre", [], [], [])]);

        if ($user->getPseudo() === "nouveau_pseudo" &&
            $user->getMail() === "nouveau@mail.com" &&
            count($user->getFavoris()) === 1) {
            echo "<< [Test de mutateurs : Success]\n";
        } else {
            echo "<< [Test de mutateurs : Fail]\n";
        }
    }

    /**
     * Teste l'ajout d'un favori à l'utilisateur.
     */
    public function testAjouterFavori() {
        $album = new Album(1, "image.png", 2023, "Titre", [], [], []);
        $user = new User(1, "utilisateur", "motdepasse", "utilisateur@example.com", false, []);

        $user->ajouterFavori($album);

        if (count($user->getFavoris()) === 1 && $user->getFavoris()[0] === $album) {
            echo "<< [Test Ajouter Favori : Success]\n";
        } else {
            echo "<< [Test Ajouter Favori : Fail]\n";
        }
    }

    /**
     * Teste la suppression d'un favori de l'utilisateur.
     */
    public function testSupprimerFavori() {
        $album = new Album(1, "image.png", 2023, "Titre", [], [], []);
        $user = new User(1, "utilisateur", "motdepasse", "utilisateur@example.com", false, [$album]);

        $user->supprimeFavori(1);

        if (empty($user->getFavoris())) {
            echo "<< [Test Supprimer Favori : Success]\n";
        } else {
            echo "<< [Test Supprimer Favori : Fail]\n";
        }
    }

    /**
     * Teste l'égalité entre deux utilisateurs.
     */
    public function testEquals() {
        $user1 = new User(1, "utilisateur", "motdepasse", "utilisateur@example.com", false, []);
        $user2 = new User(1, "utilisateur", "motdepasse", "utilisateur@example.com", false, []);
        $user3 = new User(2, "autre_utilisateur", "motdepasse", "autre@example.com", false, []);

        if ($user1->equals($user2) && !$user1->equals($user3)) {
            echo "<< [Test Equals : Success]\n";
        } else {
            echo "<< [Test Equals : Fail]\n";
        }
    }
}

// Exécution des tests
$userTest = new UserTest();
$userTest->runTests();
?>
