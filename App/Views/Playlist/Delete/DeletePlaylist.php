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

if (isset($_SESSION["id"]) && isset($_GET["delete"]) && isset($_GET["idP"])) {
    $tableToUpdate = $_GET["delete"];
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
        case 'UnknowId':
            $unknowId = true;
            break;

        default:
            $unknowId = true;
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
    <title>Suppression - Playlist</title>
    <link rel="stylesheet" href="/Public/Css/Playlist/Delete/delete-playlist-style.css">
</head>
<body>
    <?php 
        include __DIR__ . '/../../Layout/Auth/NavBar.php';
    ?>
    <?php
        include __DIR__ . '/../../Layout/Home/NavGenerique.php';
    ?>
    <div class="warning-message">
        <?php if ($errorDetected) : ?>
            <div class="erreur-container">
                <p class="erreur-suppression-playlist">
                    <?php if ($alreadyExists) : ?>
                        Cette playlist existe déjà, veuillez changer de nom....
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
        <p>Êtes-vous sûr de vouloir supprimer la playlist "<strong><?= $playlist->getNomPlaylist() ?></strong>" ?</p>
        <form action="/App/Controllers/Playlist/DeletePlaylistController.php?delete=PLAYLIST" method="post">
            <input type="hidden" name="idPlaylist" value="<?= $playlist->getIdPlaylist() ?>">
            <input type="submit" value="Oui, supprimer la playlist" class="custom-delete-submit-button">
        </form>
        <button>
            <a href="/App/Views/Playlist/Playlists.php">Annuler</a>
        </button>
    </div>