<?php

declare(strict_types=1);

namespace Test\DatabaseTest;

require_once __DIR__ . '/../../App/Autoloader/autoloader.php';

use App\Autoloader\Autoloader;
use Database\DatabaseConnection\ConnexionBDD;
use PDO;

Autoloader::register();

class ConnexionBDDTest {
    /**
     * Teste la fonction d'obtention de la connexion à la base de données.
     */
    function testObtenirConnexion()
    {
        $connexion = new ConnexionBDD();
        $pdo = $connexion::obtenir_connexion();

        if ($pdo instanceof PDO) {
            echo "Test Obtenir Connexion : Success\n";
        } else {
            echo "Test Obtenir Connexion : Fail\n";
        }
    }

    /**
     * Teste la fonction d'initialisation de la connexion à la base de données.
     */
    function testInitDB()
    {
        $connexion = new ConnexionBDD();
        $pdo = $connexion->init_DB();

        if ($pdo instanceof PDO && $pdo->getAttribute(PDO::ATTR_ERRMODE) === PDO::ERRMODE_EXCEPTION) {
            echo "Test Init DB : Success\n";
        } else {
            echo "Test Init DB : Fail\n";
        }
    }
}

// Appeler les fonctions de test
$classe = new ConnexionBDDTest();
$classe->testObtenirConnexion();
$classe->testInitDB();

?>
