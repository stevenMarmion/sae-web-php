<?php

declare(strict_types=1);

namespace App\Parser;

require_once __DIR__ . '/../Autoloader/autoloader.php';
require_once __DIR__. ' /../../Config/vendor/autoload.php';

use App\Autoloader\Autoloader;
use Exception;
use Symfony\Component\Yaml\Yaml;
use Throwable;

Autoloader::register();

/**
 * Classe YamlParser pour parser les fichiers YAML.
 */
class YamlParser {

    /**
     * Fonction statique pour parser un fichier YAML.
     *
     * @param string $file Chemin du fichier YAML à parser.
     * @return array Les données parsées du fichier YAML.
     */
    static function parser($file) {
        try {
            $data = Yaml::parseFile(__DIR__ . '/../../DataRessources/' . $file);
            return $data;
        } catch (Throwable $th) {
            throw new Exception("Le fichier YAML spécifié n'existe pas.\n");
        }
    }
}

?>
