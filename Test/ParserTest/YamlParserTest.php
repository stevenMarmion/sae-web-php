<?php

declare(strict_types=1);

namespace Test\ParserTest;

require_once __DIR__ . '/../../App/Autoloader/autoloader.php';

use App\Autoloader\Autoloader;
use App\Parser\YamlParser;
use Exception;

Autoloader::register();

/**
 * Teste la classe YamlParser.
 */
class YamlParserTest
{
    /**
     * Teste la méthode parser avec un fichier YAML valide.
     */
    public function testParserFichierExistant(): void
    {
        $yamlFile = 'extrait.yml'; // fichier existant
        $result = YamlParser::parser($yamlFile);

        if (is_array($result)) {
            echo "<< [Test Parser avec fichier YAML valide : Success]\n";
        } else {
            echo "<< [Test Parser avec fichier YAML valide : Fail]\n";
        }
    }

    /**
     * Teste la méthode parser avec un fichier YAML inexistant.
     */
    public function testParserFichierInexistant(): void
    {
        $yamlFile = 'fichier_inexistant.yaml'; // est un fichier inexistant

        try {
            YamlParser::parser($yamlFile);
            echo "<< [Test Parser avec fichier YAML inexistant : Fail]\n";
        } catch (Exception $e) {
            if ($e->getMessage() === "Le fichier YAML spécifié n'existe pas.\n") {
                echo "<< [Test Parser avec fichier YAML inexistant : Success]\n";
            } else {
                echo "<< [Test Parser avec fichier YAML inexistant : Fail]\n";
            }
        }
    }

}

// Exécution des tests
$yamlParserTest = new YamlParserTest();
$yamlParserTest->testParserFichierExistant();
$yamlParserTest->testParserFichierInexistant();

?>
