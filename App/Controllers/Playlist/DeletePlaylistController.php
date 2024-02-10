<?php

declare(strict_types=1);

namespace App\Controllers\Playlist;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudContenir;
use App\Models\EntityOperations\CrudPosseder;
use App\Models\Playlist;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudPlaylist;

Autoloader::register();

session_start();

$db = new ConnexionBDD();
$crudPlaylist = new CrudPlaylist($db::obtenir_connexion());
$crudContenir = new CrudContenir($db::obtenir_connexion());
$crudPosseder = new CrudPosseder($db::obtenir_connexion());

if (isset($_SESSION["id"]) && isset($_SERVER["REQUEST_METHOD"]) && isset($_GET["delete"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $idUser = intval($_SESSION["id"]);
        $tableToUpdate = $_GET["delete"]; // Forcément PLAYLIST sinon aucun agissement
        switch ($tableToUpdate) {
            case "PLAYLIST":
                $idP = intval($_POST["idPlaylist"]);
                $actionValid = $crudPosseder->supprimerRelation($idUser, $idP);
                if ($actionValid) {
                    $actionValid = $crudContenir->supprimerPlaylist($idP);
                    if ($actionValid) {
                        $actionValid = $crudPlaylist->supprimerPlaylist($idUser, $idP);
                        if ($actionValid) {
                            header('Location: /App/Views/Playlist/Playlists.php');
                            exit();
                        } else {
                            header('Location: /App/Views/Playlist/Delete/DeletePlaylist.php?error=UnknowId');
                            exit();
                        }
                    } else {
                        header('Location: /App/Views/Playlist/Delete/DeletePlaylist.php?error=UnknowId');
                        exit();
                    }
                } else {
                    header('Location: /App/Views/Playlist/Delete/DeletePlaylist.php?error=UnknowId');
                    exit();
                }
        }
    }
}


?>