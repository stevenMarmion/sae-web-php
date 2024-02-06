<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudAlbum;
use App\Models\EntityOperations\CrudArtiste;
use Database\DatabaseConnection\ConnexionBDD;
use App\Views\Base\head;
use App\Views\Base\footer;
use App\Models\Album;

Autoloader::register();

session_start();

if (isset($_GET["id"])) {
    $idAlbum = $_GET["id"];
    $instance = new ConnexionBDD();
    $crudAlbum = new CrudAlbum($instance::obtenir_connexion());
    $crudArtiste = new CrudArtiste($instance::obtenir_connexion());
    $album = $crudAlbum->obtenirAlbumParId(intval($idAlbum));
    $img = $album["img"] == "" ? "base.jpg" : $album["img"];

    if(file_exists("../../../DataRessources/images/".$img) && (strstr($img,"%")===false)) {
        $img = $album["img"] == "" ? "base.jpg" : $album["img"];
    }
    else {
        $img = "base.jpg";
    }

    $genres="";
    $taille=sizeof($crudAlbum->obtenirGenresAlbum(intval($idAlbum)));
    foreach ($crudAlbum->obtenirGenresAlbum(intval($idAlbum)) as $genre){
        if($taille == 1){
            $genres.=$genre["nomG"];
        }
        else{
            $genres.=$genre["nomG"].", ";
            $taille--;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Details/details-albums-style.css">
    <title>Details - Albums</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../Layout/Auth/NavBar.php';
    ?>
    <?php if (isset($_GET['id']) && $_GET['id']) : ?>
        <h1>
            <?=$album["titre"]?>
        </h1>
        <img src="<?= '../../../DataRessources/images/'.$img?>" alt='image album' class="imageAlbum">
        <p>
            Date de sortie : <?=$album["dateDeSortie"] ?>
        </p>
        <p>
            Compositeur(s) : <?=$crudArtiste->obtenirArtisteParId($crudAlbum->obtenirCompositeurId(intval($idAlbum))["idA"])["nomA"]?>
        </p>
        <p>
            Interprete(s) : <?=$crudArtiste->obtenirArtisteParId($crudAlbum->obtenirInterpreteId(intval($idAlbum))["idA"])["nomA"]?>
        </p>
        <p>
            Genre(s) : <?= $genres ?>
        </p>
    <?php endif; ?>
</body>
</html>