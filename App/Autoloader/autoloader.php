<?php

namespace App\Autoloader;

/** 
 * Classe Autoloader qui permet de charger automatiquement les classes
 */
class Autoloader
{
    /**
     * Autoload provenant de la documentation officielle de PHP
     */
    public static function register()
    {
        spl_autoload_register(function ($class) {
            echo $class . PHP_EOL;
            echo "<br>";
            $file = "/" . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
            echo "file : " . $file;
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
            return false;
        });
    }
}

?>