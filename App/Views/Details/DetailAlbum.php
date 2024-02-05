
<?php

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
}


$instance = new ConnexionBDD();
$crudAlbum = new CrudAlbum($instance::obtenir_connexion());
$crudArtiste = new CrudArtiste($instance::obtenir_connexion());
$album = $crudAlbum->obtenirAlbumParId($idAlbum);
$img = $album["img"] == "" ? "base.jpg" : $album["img"];

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
    <?php
        if(file_exists("../../../DataRessources/images/".$img) && (strstr($img,"%")===false)) {
            $img = $album["img"] == "" ? "base.jpg" : $album["img"];
        }
        else {
            $img = "base.jpg";
        }
    ?>
    <h2> <?=$album["titre"]?> </h2>
    <img src="<?= '../../../DataRessources/images/'.$img?>" alt='image album' class="imageAlbum">
    <h3>Date de sortie : <?=$album["dateDeSortie"] ?></h3>
    <h3>Compositeur(s) : <?=$crudArtiste->obtenirArtisteParId($crudAlbum->obtenirCompositeurId($idAlbum)["idA"])["nomA"]?></h3>
    <h3>Interprete(s) : <?=$crudArtiste->obtenirArtisteParId($crudAlbum->obtenirInterpreteId($idAlbum)["idA"])["nomA"]?></h3>
    <?php
        $genres="";
        $taille=sizeof($crudAlbum->obtenirGenresAlbum($idAlbum));
        foreach ($crudAlbum->obtenirGenresAlbum($idAlbum) as $genre){
            if($taille==1){
                $genres.=$genre["nomG"];
            }
            else{
                $genres.=$genre["nomG"].", ";
                $taille--;
            }
        }
    ?>
    <h3>Genre(s) : <?= $genres ?> </h3>
</body>
</html>