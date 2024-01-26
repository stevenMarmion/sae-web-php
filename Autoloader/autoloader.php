<?php

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
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /**
     * Inclue le fichier correspondant à notre classe
     * @param $class string Le nom de la classe à charger
     */
    public static function autoload($class)
    {
        // Convertir le nom de la classe en chemin du fichier
        $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';

        // Vérifier si le fichier existe
        if (file_exists($file)) {
            // Inclure le fichier
            require_once $file;
        }
    }
}


