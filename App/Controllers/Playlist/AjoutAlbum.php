<?php

use App\Autoloader\Autoloader;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\EntityOperations\CrudPlaylist;

require_once '../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once '../../Models/EntityOperations/CrudPlaylist.php';

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
    $idAlbum = $_POST['idAlbum'];
    $idPlaylist = $_POST['idPlaylist'];
    echo $crudPlaylist->ajouterAlbumPlaylist($idPlaylist, $idAlbum);
    header('Location: /App/Views/Playlist/Playlists.php');
    exit();
}

?>