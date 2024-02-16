<?php

declare(strict_types=1);

namespace App\Controllers\Playlist;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use App\Models\Playlist;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudPlaylist;

Autoloader::register();

session_start();

$db = new ConnexionBDD();
$crudPlaylist = new CrudPlaylist($db::obtenir_connexion());

if (isset($_SESSION["id"]) && isset($_SERVER["REQUEST_METHOD"]) && isset($_GET["update"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $idUser = intval($_SESSION["id"]);
        $tableToUpdate = $_GET["update"]; // Forcément PLAYLIST sinon aucun agissement
        switch ($tableToUpdate) {
            case "PLAYLIST":
                $idP = intval($_POST["idPlaylist"]);

                $currentPlaylistInBD = $crudPlaylist->obtenirPlaylistParId($idP);

                if (is_array($currentPlaylistInBD)) {
                    $nomPlaylist = $_POST["nomPlaylist"];
                    $ancienneImg = $_POST["currentImgPlaylist"];
                    $imgPlaylist = $_POST["imgPlaylist"];

                    if (empty($imgPlaylist)) {
                        $img = $ancienneImg;
                    }
                    else {
                        $img = $imgPlaylist;
                    }

                    $playlist = new Playlist($idP, $idUser, $img, $nomPlaylist);
                    
                    if ($currentPlaylistInBD["nomPlaylist"] == $playlist->getNomPlaylist()) {
                            $actionValid = $crudPlaylist->modifierPlaylist($idP, $idUser, $playlist);
                            if ($actionValid) {
                                header('Location: /App/Views/Playlist/Update/UpdatePlaylist.php?update=PLAYLIST&idP=' . $idP . '');
                                exit();
                            } else {
                                header('Location: /App/Views/Playlist/Update/UpdatePlaylist.php?error=AlreayExists');
                                exit();
                            }
                    }
                    else {
                        $actionValid = $crudPlaylist->modifierPlaylistAvecVerfiDupliNom($idP, $idUser, $playlist);
                        if ($actionValid) {
                            header('Location: /App/Views/Playlist/Update/UpdatePlaylist.php?update=PLAYLIST&idP=' . $idP . '');
                            exit();
                        } else {
                            header('Location: /App/Views/Playlist/Update/UpdatePlaylist.php?update=PLAYLIST&idP=' . $idP . '&error=AlreayExists');
                            exit();
                        }
                    }
                }
            }
        }
}


?>