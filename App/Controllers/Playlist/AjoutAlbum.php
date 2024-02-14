<?php

declare(strict_types=1);

namespace App\Controllers\Playlist;

require_once __DIR__ . "/../../Autoloader/autoloader.php";

use App\Autoloader\Autoloader;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\EntityOperations\CrudPlaylist;

Autoloader::register();

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['idAlbum']) && isset($_POST['idPlaylist'])) {
        ajoutAlbum();
    } else {
        header('Location: /App/Views/Playlist/Playlists.php?error=2');
        exit();
    }
}
header('Location: /App/Views/Playlist/Playlists.php?error=2');
exit();

function ajoutAlbum() {
    $instance = new ConnexionBDD();
    $crudPlaylist = new CrudPlaylist($instance::obtenir_connexion());
    $idAlbum = intval($_POST['idAlbum']);
    $idPlaylist = intval($_POST['idPlaylist']);
    echo $crudPlaylist->ajouterAlbumPlaylist($idPlaylist, $idAlbum);
    header('Location: /App/Views/Playlist/Playlists.php');
    exit();
}

?>