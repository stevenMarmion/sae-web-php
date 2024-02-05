<?php


use App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudAlbum;
use App\Models\EntityOperations\CrudArtiste;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\Album;
use App\Views\Base\head;
use App\Views\Base\footer;
use App\Models\EntityOperations\CrudGenre;

require_once '../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once '../../Models/EntityOperations/CrudAlbum.php';
require_once '../../Models/EntityOperations/CrudArtiste.php';
require_once '../../Models/EntityOperations/CrudGenre.php';
require_once '../../Models/Album.php';

Autoloader::register();

$instance = new ConnexionBDD();
$crudAlbum = new CrudAlbum($instance::obtenir_connexion());
$crudArtiste = new CrudArtiste($instance::obtenir_connexion());
$listeAlbum = $crudAlbum->obtenirAlbumsParDerniereSortie();
$listeAlbumObjet = [];
foreach ($listeAlbum as $album) {
    $idC = $crudAlbum->obtenirCompositeurId(intval($album["id"]))["idA"];
    $idI = $crudAlbum->obtenirInterpreteId(intval($album["id"]))["idA"];
    $listeGenre = $crudAlbum->obtenirGenresAlbum($album["id"]);
    $al = new Album($idM=intval($album["id"]),
                    $img=$album["img"] ?? "",
                    $dateDeSortie=intval($album["dateDeSortie"]),
                    $title=$album["titre"],
                    $compositeur=$crudArtiste->obtenirArtisteParId($idC),
                    $interprete=$crudArtiste->obtenirArtisteParId($idI),
                    $genre=$listeGenre === false ? [] : $listeGenre,
                );
    array_push($listeAlbumObjet, $al);
}

include_once '../Base/head.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>acceuil</title>
</head>
<body>
    <div class="nav">
        <a href="#">Mes playlists</a>
        <a href="#">Recherche utilisateur</a>
        <a href="#">Profile</a>
    </div>
    <h1> Voici les dernieres sorties ! </h1>
    <ul>
    <?php
    foreach($listeAlbumObjet as $album){
        if($album->getCompositeurs()["nomA"]==$album->getInterpretes()["nomA"]){
            $img = $album->getImg();
            if(file_exists("../../../DataRessources/images/".$img) && (strstr($img,"%")===false)){
                $img = $album->getImg() == "" ? "base.jpg" : $album->getImg();
            }
            else{
                $img = "base.jpg";
            }
            ?>
                <li class="album"><img src="<?= '../../../DataRessources/images/'.$img?>"alt="image album" class="imageAlbum"> <h4 class="titreAlbum"><?= $album->getTitre()?></h4> <div class='interprete&compositeur'>interprete et compositeur : <?= $album->getCompositeurs()["nomA"]?></div>
            <?php }
        else{
            ?>
            <li class="album"><img src="<?= '../../../DataRessources/images/'.$img?>"alt="image album" class="imageAlbum"> <h4 class="titreAlbum"><?= $album->getTitre()?></h4> <div class='interprete&compositeur'>compositeur : <?= $album->getCompositeurs()["nomA"]?> </div><div class='interprete&compositeur'>interprete : <?= $album->getInterpretes()["nomA"]?> </div>
            <?php
        }
        echo "<a href='/App/Views/Details/detailAlbum.php?id=".$album->getId()."'><button>voir plus</button></a></li>";
    }
    ?>

    <!-- il faut le changer c'est juste pour que les images soit de la bonne taille -->
    <style>
        .imageAlbum{
            width: 200px;
            height: 200px;
        }
    </style>


    </ul>
    <div class="infoAlbum" hidden>
    </div>
    <script src="../../../Public/JS/affichageInfo.js"></script>
    </body>
</html>