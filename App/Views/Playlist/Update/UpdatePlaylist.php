<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

require_once __DIR__ . '/../../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use App\Models\Playlist;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudPlaylist;

Autoloader::register();

session_start();

$db = new ConnexionBDD();
$crudPlaylist = new CrudPlaylist($db::obtenir_connexion());

if (isset($_SESSION["id"]) && isset($_GET["update"]) && isset($_GET["idP"])) {
    $tableToUpdate = $_GET["update"];
    switch ($tableToUpdate) {
        case "PLAYLIST":
            $idP = intval($_GET["idP"]);
            $idUser = intval($_SESSION["id"]);
            $currentPlaylist = $crudPlaylist->obtenirPlaylistParId($idP);
            if (is_array($currentPlaylist)) { // nous obtenons bel et bien la playlist existante
                $playlist = new Playlist($idP, $idUser, $currentPlaylist["imgPlaylist"], $currentPlaylist["nomPlaylist"]);
            }
        } 
}

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
    <title>Modification de la playlist</title>
    <link rel="stylesheet" href="/Public/Css/Playlist/Update/update-playlist-style.css">
</head>
<body>
    <?php 
        include __DIR__ . '/../../Layout/Auth/NavBar.php';
    ?>
    <?php
        include __DIR__ . '/../../Layout/Home/NavGenerique.php';
    ?>
    <section class="container">
        <h1>Modification de la playlist <strong><?= $playlist->getNomPlaylist() ?></strong> </h1>
        <form action="/App/Controllers/Playlist/UpdatePlaylistController.php?update=PLAYLIST" method="post" class="styled-form">
            <label for="nomPlaylist">Nom de la playlist :</label>
            <input type="text" id="nomPlaylist" name="nomPlaylist" value="<?= $playlist->getNomPlaylist() ?>" required>
            <?php if ($errorDetected) : ?>
                <div class="erreur-container">
                    <p class="erreur-creation-playlist">
                        <?php if ($alreadyExists) : ?>
                            Cette playlist existe déjà, veuillez changer de nom....
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
            
            <label for="imgPlaylist">Image de la playlist :</label>
            <div class="image-preview">
                <img src="/DataRessources/imagePlaylist/<?= $playlist->getImg() ?>" alt="Image de la playlist">
                <input type="hidden" name="currentImgPlaylist" value="<?= $playlist->getImg() ?>">
            </div>
            <label class="custom-file-upload">
                Choisir une image
                <input type="file" id="imgPlaylist" name="imgPlaylist" accept="image/*">
            </label>
            
            <input type="hidden" name="idPlaylist" value="<?= $playlist->getIdPlaylist() ?>">
            
            <input type="submit" value="Enregistrer les modifications" class="custom-submit-button">
        </form>
        <div class="cancel-button-container">
            <button class="button-cancel">
                <a href="/App/Views/Playlist/Playlists.php">Annuler</a>
            </button>
        </div>
    </section>
</body>
</html>
