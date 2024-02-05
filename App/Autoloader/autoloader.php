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
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    static function autoload($class) {
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        require __DIR__ . '/../../' . $file . '.php';
    }
}

?>