<?php


use App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudAlbum;
use App\Models\EntityOperations\CrudArtiste;
use App\Models\EntityOperations\CrudPlaylist;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\Album;

require_once '../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once '../../Models/EntityOperations/CrudAlbum.php';
require_once '../../Models/EntityOperations/CrudArtiste.php';
require_once '../../Models/EntityOperations/CrudPlaylist.php';

require_once '../../Models/Album.php';

Autoloader::register();
session_start();

$instance = new ConnexionBDD();
$crudAlbum = new CrudAlbum($instance::obtenir_connexion());
$crudArtiste = new CrudArtiste($instance::obtenir_connexion());
$crudPlaylist = new CrudPlaylist($instance::obtenir_connexion());
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
var_dump($_SESSION);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Acceuil/like.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/Public/JS/like.js"></script>
    <script src="/Public/JS/verifLike.js"></script>
    <title>acceuil</title>
</head>
<body>
    <div class="nav">
        <a href="/App/Views/playlist/Playlists.php">Mes playlists</a>
        <a href="#">Recherche utilisateur</a>
        <a href="#">Profile</a>
    </div>
    <h1> Voici les dernieres sorties ! </h1>
    <ul>
    <?php
    foreach($listeAlbumObjet as $album){
        $img = $album->getImg();
        if(file_exists("../../../DataRessources/images/".$img) && (strstr($img,"%")===false)){
            $img = $album->getImg() == "" ? "base.jpg" : $album->getImg();
        }
        else{
            $img = "base.jpg";
        }
        ?>
        
        <div id="main-content">
  <div>
    
    <button activer="false" onclick="like(<?=$album->getId()?>)" name="like<?=$album->getId()?>"><img src="/DataRessources/like/coeur_vide.jpg" alt=""></button>
    <script>estLike(<?=$album->getId()?>)</script>
        
        <?php
        if($album->getCompositeur()["nomA"]==$album->getInterprete()["nomA"]){
            ?>
                <li class="album"><img src="<?= '../../../DataRessources/images/'.$img?>"alt="image album" class="imageAlbum"> 
                <h4 class="titreAlbum"><?= $album->getTitre()?></h4> 
                <div class='interprete&compositeur'>interprete et compositeur : <?= $album->getCompositeur()["nomA"]?></div>
            <?php }
        else{
            ?>
                <li class="album">
                    <img src="<?= '../../../DataRessources/images/'.$img?>"alt="image album" class="imageAlbum">
                    <h4 class="titreAlbum"><?= $album->getTitre()?></h4> 
                    <div class='interprete&compositeur'>compositeur : <?= $album->getCompositeur()["nomA"]?> </div>
                    <div class='interprete&compositeur'>interprete : <?= $album->getInterprete()["nomA"]?> </div>
            <?php
        }
        $playlistSansAlbumId = $crudPlaylist->obtenirPlaylistSansIdAlbum($album->getId(),$_SESSION["idU"]);
        if(sizeof($playlistSansAlbumId)==0){
            echo "<h4>aucune playlist disponible</h4>";        
        }
        else{
            echo "<h4>ajouter à une playlist</h4>";
        ?>
        <form action="/App/Controllers/Playlist/AjoutAlbum.php" method="post">
                        <select name="idPlaylist">
                            <?php
                            foreach($crudPlaylist->obtenirPlaylistSansIdAlbum($album->getId(),$_SESSION["idU"]) as $playlist){
                                echo "<option value=".$playlist["idPlaylist"].">".$playlist["nomPlaylist"]."</option>";
                            }
                            ?>
                        </select>
                        <input type="hidden" name="idAlbum" value="<?= $album->getId()?>">
                        <input type="submit" value="ajouter à une playlist">
        </form>
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
    <script src="../../../Public/JS/like.js"></script>
    </body>
</html>