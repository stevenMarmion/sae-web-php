<?php

declare(strict_types=1);

namespace App\Controllers\Album;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use App\Autoloader\Autoloader;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\EntityOperations\CrudAlbum;
use App\Models\EntityOperations\CrudPlaylist;

Autoloader::register();

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['idAlbum'])) {
        disliker();
    } else {
        exit();
    }
}
// exit();

function disliker() {

    $instance = new ConnexionBDD();
    $crudAlbum = new CrudAlbum($instance::obtenir_connexion());
    $crudPlaylist = new CrudPlaylist($instance::obtenir_connexion());
    $idAlbum = $_POST['idAlbum'];
    $crudAlbum->supprimerlike($_SESSION["id"], $idAlbum);
    $crudPlaylist->supprimerAlbumPlaylist($crudPlaylist->PlaylistFavoris($_SESSION["id"])[0]["idPlaylist"], $idAlbum);
    exit();
}
?>