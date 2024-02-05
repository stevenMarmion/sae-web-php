<?php

use App\Autoloader\Autoloader;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\EntityOperations\CrudPlaylist;

require_once '../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once '../../Models/EntityOperations/CrudPlaylist.php';

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
    $idCreateur = $_SESSION['idU'];
    echo $crudPlaylist->ajouterPlaylist($idCreateur, $nomPlaylist, $imgPlaylist);
    header('Location: /App/Views/playlist/Playlists.php');
    exit();
}

?>