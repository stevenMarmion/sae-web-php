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
    if (isset($_POST['nomPlaylist']) && isset($_POST['imgPlaylist'])) {
        creationPlaylist();
    } else {
        header('Location: /App/Views/playlist/Playlists.php?error=1');
        exit();
    }
}

function creationPlaylist() {
    $instance = new ConnexionBDD();
    $crudPlaylist = new CrudPlaylist($instance::obtenir_connexion());
    $nomPlaylist = $_POST['nomPlaylist'];
    $imgPlaylist = ($_POST['imgPlaylist']== "" ? "base.jpg" : $_POST['imgPlaylist']);
    $idCreateur = intval($_SESSION['id']);
    $crudPlaylist->ajouterPlaylist($idCreateur, $nomPlaylist, $imgPlaylist);
    header('Location: /App/Views/Playlist/Playlists.php');
    exit();
}

?>