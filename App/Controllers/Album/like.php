<?php

declare(strict_types=1);

namespace App\Controllers\Album;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use App\Autoloader\Autoloader;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\EntityOperations\CrudAlbum;
use App\Models\EntityOperations\CrudPlaylist;
use App\Models\EntityOperations\CrudPosseder;

Autoloader::register();

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['idAlbum'])) {
        liker();
    } else {
        exit();
    }
}

function liker() {

    $instance = new ConnexionBDD();
    $crudAlbum = new CrudAlbum($instance::obtenir_connexion());
    $idAlbum = intval($_POST['idAlbum']);
    $crudAlbum->ajouterLike(intval($_SESSION["id"]), $idAlbum);
    $crudPlaylist = new CrudPlaylist($instance::obtenir_connexion());
    $crudPosseder = new CrudPosseder($instance::obtenir_connexion());
    if(count($crudPlaylist->PlaylistFavoris(intval($_SESSION["id"]))) == 0){
        $nomPlaylist = "Like";
        $imgPlaylist = "like.jpg";
        $idCreateur = intval($_SESSION["id"]);
        $crudPlaylist->ajouterPlaylist($idCreateur, $nomPlaylist, $imgPlaylist);
        $crudPosseder->ajouterRelation($idCreateur,$idAlbum);
    }
    echo $crudPlaylist->ajouterAlbumPlaylist($crudPlaylist->PlaylistFavoris(intval($_SESSION["id"]))[0]["idPlaylist"], $idAlbum);
    exit();
}
?>