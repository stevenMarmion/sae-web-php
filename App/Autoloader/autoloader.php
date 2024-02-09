<?php

declare(strict_types=1);

namespace App\Autoloader;

/** 
 * Classe Autoloader qui permet de charger automatiquement les classes
 */
class Autoloader
{
    /**
     * Enregistre l'autoloader pour charger automatiquement les classes.
     * 
     * @return void
     */
    public static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Fonction de chargement automatique des classes.
     * 
     * @param string $class Le nom de la classe à charger.
     * @return void
     */
    static function autoload($class) {
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        require __DIR__ . '/../../' . $file . '.php';
    }
}

?>
