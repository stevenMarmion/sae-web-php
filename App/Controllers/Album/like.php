<?php

use App\Autoloader\Autoloader;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\EntityOperations\CrudAlbum;

require_once '../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once '../../Models/EntityOperations/CrudAlbum.php';

Autoloader::register();

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['idAlbum']) && isset($_POST['idPlaylist'])) {
        liker();
    } else {
        header('Location: /App/Views/Home/Acceuil.php?error=2');
        exit();
    }
}
header('Location: /App/Views/Home/Acceuil.php?error=2');
exit();

function liker() {
    $instance = new ConnexionBDD();
    $crudAlbum = new CrudAlbum($instance::obtenir_connexion());
    $idAlbum = $_POST['idAlbum'];
    echo $crudAlbum->ajouterAlbumPlaylist($idPlaylist, $idAlbum);
    header('Location: /App/Views/Home/Acceuil.php');
    exit();
}
?>