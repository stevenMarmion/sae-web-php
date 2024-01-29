<?php

namespace App\Autoloader;

/** 
 * Classe Autoloader qui permet de charger automatiquement les classes
 */
class Autoloader
{
    /**
     * Enregistre notre autoloader
     */
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }
}
Autoloader::register();


