<?php

namespace Parser;

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

class YamlParser {

    static function parser($file) {

        if (!file_exists(__DIR__ . '/../Datas/fixtures/' . $file)) {
            die("Le fichier YAML spécifié n'existe pas.\n");
        }

        // Charger le fichier YAML
        $data = Yaml::parseFile(__DIR__ . '/../Datas/fixtures/' . $file);

        return $data;
    }
}

?>