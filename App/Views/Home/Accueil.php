<?php

declare(strict_types=1);

namespace App\Views\Home;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudAlbum;
use App\Models\EntityOperations\CrudArtiste;
use App\Models\EntityOperations\CrudPlaylist;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\Album;
use App\Views\Base\head;
use App\Views\Base\footer;
use App\Models\EntityOperations\CrudGenre;

Autoloader::register();

session_start();
// id utilisateur stocké dans $_SESSION["id"]

$instance = new ConnexionBDD();
$crudAlbum = new CrudAlbum($instance::obtenir_connexion());
$crudArtiste = new CrudArtiste($instance::obtenir_connexion());
$crudPlaylist = new CrudPlaylist($instance::obtenir_connexion());
$listeAlbum = $crudAlbum->obtenirAlbumsParDerniereSortie();
$listeAlbumObjet = [];
foreach ($listeAlbum as $album) {
    $idC = $crudAlbum->obtenirCompositeurId(intval($album["id"]))["idA"];
    $idI = $crudAlbum->obtenirInterpreteId(intval($album["id"]))["idA"];
    $listeGenre = $crudAlbum->obtenirGenresAlbum(intval($album["id"]));
    $al = new Album($idM=intval($album["id"]),
                    $img=$album["img"] ?? "",
                    $dateDeSortie=intval($album["dateDeSortie"]),
                    $title=$album["titre"],
                    $compositeur=$crudArtiste->obtenirArtisteParId(intval($idC)) ?: [],
                    $interprete=$crudArtiste->obtenirArtisteParId(intval($idI)) ?: [],
                    $genre = $listeGenre == false ? [] : $listeGenre,
                );
    array_push($listeAlbumObjet, $al);
}

//include_once '../Base/head.php';

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

    <!-- <?php
    // foreach($listeAlbumObjet as $album){
    //     $img = $album->getImg();
    //     if(file_exists("../../../DataRessources/images/".$img) && (strstr($img,"%")===false)){
    //         $img = $album->getImg() == "" ? "base.jpg" : $album->getImg();
    //     }
    //     else{
    //         $img = "base.jpg";
    //     }
        ?> -->

    <link rel="stylesheet" href="/Public/Css/Home/home-style.css">
    <title>Accueil - Consultation</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../Layout/Auth/NavBar.php';
    ?>
    <?php
        include __DIR__ . '/../Layout/Home/NavGenerique.php';
    ?>
    <p class="message-user-accueil">Bonjour <strong><?= $_SESSION["pseudo"] ?>
        </strong>, nous espérons que tout va bien aujourd'hui...
    </p>
    <h1>
        Voici les dernieres sorties !
    </h1>
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
                    <li class="album">
                        <img src="<?= '../../../DataRessources/images/'.$img?>"alt="image album" class="imageAlbum">
                        <button activer="false" onclick="like(<?=$album->getId()?>)" name="like<?=$album->getId()?>"><img src="/DataRessources/like/coeur_vide.jpg" alt=""></button>
                        <script>estLike(<?=$album->getId()?>)</script>
                        <h4 class="titreAlbum">
                            <?= $album->getTitre()?>
                        </h4> 
                        <div class='interpreteAndcompositeur'>
                            Interprete(s) et Compositeur(s) : <?= $album->getCompositeurs()["nomA"]?>
                        </div>
                    <?php 
                } else {
                    ?>
                    <li class="album">

                        <img src="<?= '../../../DataRessources/images/'.$img?>"alt="image album" class="imageAlbum">

                        <button activer="false" onclick="like(<?=$album->getId()?>)" name="like<?=$album->getId()?>"><img src="/DataRessources/like/coeur_vide.jpg" alt=""></button>
                        <script>estLike(<?=$album->getId()?>)</script>

                        <h4 class="titreAlbum">
                            <?= $album->getTitre()?>
                        </h4> 
                        <div class='interpreteAndcompositeur'>
                            Compositeur(s) : <?= $album->getCompositeurs()["nomA"]?> 
                        </div>
                        <div class='interpreteAndcompositeur'>
                            Interprete(s) : <?= $album->getInterpretes()["nomA"]?>
                        </div>
                    <?php
                }
                ?>
                        <a href='/App/Views/Details/DetailAlbum.php?id=<?= $album->getId() ?>'>
                            <button>voir plus</button>
                        </a>

                        <?php
                        $playlistSansAlbumId = $crudPlaylist->obtenirPlaylistSansIdAlbum($album->getId(),$_SESSION["id"]);
                        if(sizeof($playlistSansAlbumId)==0){
                            echo "<h4>aucune playlist disponible</h4>";        
                        }
                        else{
                            echo "<h4>ajouter à une playlist</h4>";
                        
                        ?>

                        <form action="/App/Controllers/Playlist/AjoutAlbum.php" method="post">
                            <select name="idPlaylist">
                                <?php
                                foreach($crudPlaylist->obtenirPlaylistSansIdAlbum($album->getId(),$_SESSION["id"]) as $playlist){
                                    echo "<option value=".$playlist["idPlaylist"].">".$playlist["nomPlaylist"]."</option>";
                                }
                                ?>
                            </select>
                            <input type="hidden" name="idAlbum" value="<?= $album->getId()?>">
                            <input type="submit" value="ajouter à une playlist">
                        </form>

                        <?php
                        }
                        ?>

                    </li>
                <?php
            }
        ?>
    </ul>
    <script src="../../../Public/JS/like.js"></script>
    </body>
</html>