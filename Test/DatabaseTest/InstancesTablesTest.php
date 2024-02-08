<?php

declare(strict_types=1);

namespace Test\DatabaseTest;

require_once __DIR__ . '/../../App/Autoloader/autoloader.php';

use Database\DatabaseConnection\InstancesTables;
use Throwable;
use PDO;
use PDOException;
use App\Autoloader\Autoloader;
use App\Parser\YamlParser;
use Database\DatabaseConnection\ConnexionBDD;

Autoloader::register();

// Instancie la tableau argv
global $argv;

$instance = new ConnexionBDD();
$tables = new InstancesTables($instance::obtenir_connexion());
$tables->create_tables($instance::obtenir_connexion(), $argv);

class InstancesTablesTest {

    public function runTests() {
        $this->testCreateTables();
        $this->testRecupArgv();
        // Ajoutez d'autres tests au besoin
    }

    public function testCreateTables() {
        // Créer un objet mock de PDO
        $mockPDO = $this->createMock(PDO::class);
        // Créer un objet InstancesTables avec le mock de PDO
        $instancesTables = new InstancesTables($mockPDO);

        // Appeler la méthode create_tables
        $instancesTables->create_tables($mockPDO, []);

        // Vérifier que les méthodes appropriées de PDO ont été appelées
        $mockPDO->expects($this->exactly(2))
                 ->method('exec')
                 ->withConsecutive(
                     [$this->stringContains('creation.sql')],
                     [$this->stringContains('insertion.sql')]
                 );

        // Appeler une méthode pour s'assurer que les attentes sont vérifiées
        $this->addToAssertionCount(1);
    }

    public function testRecupArgv() {
        // Créer un objet InstancesTables
        $instancesTables = new InstancesTables(null);

        // Appeler la méthode recup_argv avec un tableau vide
        $result = $instancesTables->recup_argv([]);

        // Vérifier que la méthode renvoie false
        $this->assertFalse($result);

        // Appeler la méthode recup_argv avec un seul élément dans le tableau
        $result = $instancesTables->recup_argv(['script.php', 'path/to/file.yaml']);

        // Vérifier que la méthode renvoie un tableau non vide
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        // Appeler la méthode recup_argv avec plus d'un élément dans le tableau
        $result = $instancesTables->recup_argv(['script.php', 'path/to/file1.yaml', 'path/to/file2.yaml']);

        // Vérifier que la méthode renvoie false
        $this->assertFalse($result);
    }
}

// Exécution des tests
$test = new InstancesTablesTest();
$test->runTests();
