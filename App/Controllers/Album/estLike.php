<?php

declare(strict_types=1);

namespace App\Controllers\Album;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use App\Autoloader\Autoloader;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\EntityOperations\CrudAlbum;

Autoloader::register();

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['idAlbum'])) {
        estLike();
    } else {
        echo "erreur";
    }
}

function estLike() {
    $instance = new ConnexionBDD();
    $crudAlbum = new CrudAlbum($instance::obtenir_connexion());
    $idAlbum = intval($_POST['idAlbum']);
    echo $crudAlbum->estLike(intval($_SESSION["id"]), $idAlbum) == true ? "true" : "false";
}
?>