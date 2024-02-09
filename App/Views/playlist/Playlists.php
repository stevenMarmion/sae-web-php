<?php

use App\Autoloader\Autoloader;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\EntityOperations\CrudPlaylist;
use App\Models\EntityOperations\CrudUser;

require_once '../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once '../../Models/EntityOperations/CrudAlbum.php';
require_once '../../Models/EntityOperations/CrudArtiste.php';
require_once '../../Models/EntityOperations/CrudPlaylist.php';
require_once '../../Models/EntityOperations/CrudUser.php';
require_once '../../Models/Album.php';
require_once '../../Models/User.php';

Autoloader::register();

session_start();

$instance = new ConnexionBDD();
$crudPlaylist = new CrudPlaylist($instance::obtenir_connexion());
$crudUser = new CrudUser($instance::obtenir_connexion());
$listePlaylist = $crudUser->obtenirPlaylistsUtilisateur($_SESSION["id"]);


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Playlists</title>
</head>
<body>
    <h1> Creer une playlist </h1>
    <form action="/App/Controllers/Playlist/CreationPlaylist.php" method="post">
        <input type="text" name="nomPlaylist" placeholder="Nom de la playlist">
        <input type="file" name="imgPlaylist" accept="image/*">
        <input type="submit" value="Creer">
    </form>
    <h1> Mes playlists </h1>
    <div class="playlists">
        <ul>
        <?php
        foreach($listePlaylist as $playlist){
        ?>

            <li>
                <img src="../../../DataRessources/imagePlaylist/<?= $playlist['imgPlaylist'] ?>" alt="image de la playlist">
                <a href="playlist.php?idP=<?= $playlist['idPlaylist'] ?>"><?= $playlist['nomPlaylist'] ?></a>
            </li>
        
        <?php
        }
        ?>
        </ul>
        <style>
            img{
                width: 100px;
                height: 100px;
            }
        </style>
    </div>
    </body>
</html>