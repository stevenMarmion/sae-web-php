<?php

declare(strict_types=1);

namespace App\Parser;

require_once __DIR__ . '/../Autoloader/autoloader.php';
require_once __DIR__. ' /../../Config/vendor/autoload.php';

use App\Autoloader\Autoloader;
use Symfony\Component\Yaml\Yaml;

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
        // On vérifie si le fichier YAML existe
        if (!file_exists(__DIR__ . '/../../DataRessources/' . $file)) {
            die("Le fichier YAML spécifié n'existe pas.\n");
        }
        // On le charge
        $data = Yaml::parseFile(__DIR__ . '/../../DataRessources/' . $file);
        return $data;
    }
}

?>
