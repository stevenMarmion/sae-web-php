<?php

namespace App\Parser;

require_once __DIR__ . '/../Autoloader/autoloader.php';
require_once __DIR__.'/../../Config/vendor/autoload.php';

use App\Autoloader\Autoloader;
use Symfony\Component\Yaml\Yaml;

Autoloader::register();

class YamlParser {

    static function parser($file) {

        if (!file_exists(__DIR__ . '/../../DataRessources/' . $file)) {
            die("Le fichier YAML spécifié n'existe pas.\n");
        }

        // Charger le fichier YAML
        $data = Yaml::parseFile(__DIR__ . '/../../DataRessources/' . $file);

        return $data;
    }
}

?>