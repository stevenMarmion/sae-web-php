<?php

use App\Autoloader\Autoloader;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\EntityOperations\CrudAlbum;
use App\Models\EntityOperations\CrudPlaylist;

require_once '../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once '../../Models/EntityOperations/CrudAlbum.php';
require_once '../../Models/EntityOperations/CrudPlaylist.php';


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
    $idAlbum = $_POST['idAlbum'];
    $crudAlbum->ajouterLike($_SESSION["idU"], $idAlbum);
    $crudPlaylist = new CrudPlaylist($instance::obtenir_connexion());
    if(count($crudPlaylist->PlaylistFavoris($_SESSION["idU"])) == 0){
        $nomPlaylist = "Aimer";
        $imgPlaylist = "like.jpg";
        $idCreateur = $_SESSION['idU'];
        $crudPlaylist->ajouterPlaylist($idCreateur, $nomPlaylist, $imgPlaylist);
    }
    echo $crudPlaylist->ajouterAlbumPlaylist($crudPlaylist->PlaylistFavoris($_SESSION["idU"])[0]["idPlaylist"], $idAlbum);
    exit();
}
?>