<?php

declare(strict_types=1);

namespace App\Controllers\Playlist;

require_once __DIR__ . "/../../Autoloader/autoloader.php";

use App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudAlbum;
use App\Models\EntityOperations\CrudPlaylist;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\EntityOperations\CrudContenir;

Autoloader::register();

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["id"]) && isset($_POST['idAl'])) {
        $idUser = intval($_SESSION["id"]);
        $idAlbum = intval($_POST['idAl']);
        $idP = intval($_POST["idP"]);
        retirerAlbum($idUser, $idAlbum, $idP);
    } else {
        header('Location: /App/Views/Playlist/Playlists.php?error=2');
        exit();
    }
}

function retirerAlbum($idUser, $idAlbum, $idP) {
    $instance = new ConnexionBDD();
    $crudContenir = new CrudContenir($instance::obtenir_connexion());
    $crudAlbum = new CrudAlbum($instance::obtenir_connexion());
    $actionValid = $crudContenir->supprimerRelation($idP, $idAlbum);
    if ($actionValid) {
        $actionValid = $crudAlbum->supprimerlike($idUser, $idAlbum);
        if ($actionValid) {
            header('Location: /App/Views/Details/DetailPlaylist.php?idP=' . $idP . '');
            exit();
        }
        else {
            header('Location: /App/Views/Details/DetailPlaylist.php?idP=' . $idP . '&error=ErrorOcurred');
            exit();
        }
    }
    else {
        header('Location: /App/Views/Details/DetailPlaylist.php?idP=' . $idP . '&error=ErrorOcurred');
        exit();
    }
}



?>