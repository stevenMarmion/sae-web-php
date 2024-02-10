<?php

declare(strict_types=1);

namespace App\Views\Playlist;

require_once __DIR__ . "/../../Autoloader/autoloader.php";

use App\Autoloader\Autoloader;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\EntityOperations\CrudPlaylist;
use App\Models\EntityOperations\CrudUser;

Autoloader::register();

session_start();

$instance = new ConnexionBDD();
$crudPlaylist = new CrudPlaylist($instance::obtenir_connexion());
$crudUser = new CrudUser($instance::obtenir_connexion());
$listePlaylist = $crudUser->obtenirPlaylistsUtilisateur($_SESSION["id"]);

$errorDetected = null;

if (isset($_GET['error'])) {
    $errorDetected = true;
    $error = $_GET['error'];
    switch ($error) {
        case 'AlreadyExists':
            $alreadyExists = true;
            break;

        default:
            $alreadyExists = true;
            break;
    }
}
else {
    $errorDetected = false;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Playlist/playlist-style.css">
    <title>Playlists</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../Layout/Auth/NavBar.php';
    ?>
    <?php
        include __DIR__ . '/../Layout/Home/NavGenerique.php';
    ?>
    <h1> Créer une playlist </h1>
    <form action="/App/Controllers/Playlist/CreationPlaylist.php" method="post">
        <input type="text" name="nomPlaylist" placeholder="Nom de la playlist" required>
        <input type="file" name="imgPlaylist" accept="image/*" required>
        <input type="submit" value="Créer ma nouvelle playlist">
    </form>
    <h1> Mes playlists </h1>
    <?php if ($errorDetected) : ?>
        <div class="erreur-container">
            <p class="erreur-creation-playlist">
                <?php if ($alreadyExists) : ?>
                    Cette playlist existe déjà, veuillez changer de nom....
                <?php endif; ?>
            </p>
        </div>
    <?php endif; ?>
    <div class="playlists">
        <ul>
        <?php
        foreach($listePlaylist as $playlist) {
        ?>

            <li>
                <img src="../../../DataRessources/imagePlaylist/<?= $playlist['imgPlaylist'] ?>" alt="image de la playlist">
                <a href="/App/Views/Details/DetailPlaylist.php?idP=<?= intval($playlist['idPlaylist']) ?>">
                    Voir : <strong><?= $playlist['nomPlaylist'] ?></strong>
                </a>
            </li>
        
        <?php
        }
        ?>
        </ul>
    </div>
    </body>
</html>