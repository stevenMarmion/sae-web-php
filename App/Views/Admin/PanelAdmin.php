<?php

declare(strict_types=1);

namespace App\Views\Admin;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudAlbum;
use \App\Models\EntityOperations\CrudUser;
use \App\Models\EntityOperations\CrudArtiste;
use \App\Models\EntityOperations\CrudGenre;

Autoloader::register();

$db = new ConnexionBDD();

$crudUser = new CrudUser($db::obtenir_connexion());
$crudAlbum = new CrudAlbum($db::obtenir_connexion());
$crudArtiste = new CrudArtiste($db::obtenir_connexion());
$crudGenre = new CrudGenre($db::obtenir_connexion());

$listeUsers = $crudUser->obtenirTousUtilisateurs();
$listeAlbums = $crudAlbum->obtenirTousAlbums();
$listeArtiste = $crudArtiste->obtenirTousArtistes();
$listeGenres = $crudGenre->obtenirTousGenres();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Admin/panel-admin-style.css">
    <title>Panel - Admin</title>
</head>
<body>
    <div class="navigation-gauche">
        <img id="logo" src="/Public/Images/logo.png" width="200">
        <nav>
            <a id="panel-admin-link" href="Details/PanelDetails.php?table=UTILISATEUR">Gérer les utilisateurs</a>
            <a id="panel-admin-link" href="Details/PanelDetails.php?table=ALBUMS">Gérer les albums</a>
            <a id="panel-admin-link" href="Details/PanelDetails.php?table=ARTISTES">Gérer les artistes</a>
            <a id="panel-admin-link" href="Details/PanelDetails.php?table=GENRES">Gérer les genres</a>
            <a id="panel-admin-link" href="/App/Views/Profil/UserProfil.php">
                <img id="icon-retour" src="/Public/Icons/back.png">    
                Revenir sur la plateforme
            </a>
        </nav>
    </div>
    <section>
        <div>
            <h1>Bienvenue dans le panel admin</h1>
        </div>
        <section class="info-appli">
            <div class="info-card">
                <div class="info-label">Nombre d'utilisateurs :</div>
                <div class="info"><?= sizeof($listeUsers) ?></div>
            </div>

            <div class="info-card">
                <div class="info-label">Nombre d'albums référencés :</div>
                <div class="info"><?= sizeof($listeAlbums) ?></div>
            </div>

            <div class="info-card">
                <div class="info-label">Nombre d'artistes référencés :</div>
                <div class="info"><?= sizeof($listeArtiste) ?></div>
            </div>

            <div class="info-card">
                <div class="info-label">Nombre de genres référencés :</div>
                <div class="info"><?= sizeof($listeGenres) ?></div>
            </div>
        </section>
    </section>
</body>
</html>