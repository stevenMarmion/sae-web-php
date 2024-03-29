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
$index = 0;

if (isset($_GET["index"]) && isset($_GET["index"])!=""){
    $index = intval($_GET["index"]);
}
$instance = new ConnexionBDD();
$crudAlbum = new CrudAlbum($instance::obtenir_connexion());
$crudArtiste = new CrudArtiste($instance::obtenir_connexion());
$crudPlaylist = new CrudPlaylist($instance::obtenir_connexion());
$listeAlbum = $crudAlbum->obtenirAlbumsParDerniereSortie();
$listeAlbumObjet = [];
$i=0;
$premier=false;
foreach ($listeAlbum as $album) {
    if($i%10==0){
        if($premier){
            array_push($listeAlbumObjet,$page);
        }
        $premier=true;
        $page=[];
    }
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
    array_push($page, $al);
    $i++;
}
array_push($listeAlbumObjet,$page);

function afficheAlbum($index,$listeAlbumObjet,$crudAlbum,$crudPlaylist){
    foreach($listeAlbumObjet[$index] as $album){
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

                <button class="like" activer="false" onclick="like(<?=$album->getId()?>)" name="like<?=$album->getId()?>">
                    <img src="/DataRessources/like/coeur_vide.jpg" alt="">
                </button>
                <script>
                    estLike(<?=$album->getId()?>)
                </script>

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
                <div class="image-container">
                    <img src="<?= '../../../DataRessources/images/'.$img?>" alt="image album" class="imageAlbum">
                    <button class="like" activer="false" onclick="like(<?=$album->getId()?>)" name="like<?=$album->getId()?>">
                        <img src="/DataRessources/like/coeur_vide.jpg" alt="">
                    </button>
                </div>
                <script>
                    estLike(<?=$album->getId()?>)
                </script>
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
                    <input type="submit" value="Ajouter à une playlist">
                </form>

                <?php
                }
                ?>

            </li>
        <?php
    }
}

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
    <section class="flex-button-pagination">
        <form action="/App/Views/Home/Accueil.php" method="get">
            <input type="hidden" name="index" value="<?= $index-1<0 ? $index : $index-1?>">
            <?php if ($index-1<0) : ?>
                <button type="submit" class="disabled-button-style" disabled>Page précédente</button>
            <?php else : ?>
                <button type="submit">Page précédente</button>
            <?php endif; ?>
        </form>
        <p>Page : <?= $index+1 ?></p>
        <form action="/App/Views/Home/Accueil.php" method="get">
            <input type="hidden" name="index" value="<?= $index+1>count($listeAlbumObjet)-1 ? $index : $index+1?>">
            <?php if ($index+1>count($listeAlbumObjet)-1) : ?>
                <button type="submit" class="disabled-button-style" disabled>Page suivante</button>
            <?php else : ?>
                <button type="submit">Page suivante</button>
            <?php endif; ?>
        </form>
    </section>
    <ul>
        <?php
        afficheAlbum($index,$listeAlbumObjet,$crudAlbum,$crudPlaylist);
        ?>
    </ul>
    <section class="flex-button-pagination">
        <form action="/App/Views/Home/Accueil.php" method="get">
            <input type="hidden" name="index" value="<?= $index-1<0 ? $index : $index-1?>">
            <?php if ($index-1<0) : ?>
                <button type="submit" class="disabled-button-style" disabled>Page précédente</button>
            <?php else : ?>
                <button type="submit">Page précédente</button>
            <?php endif; ?>
        </form>
        <p>Page : <?= $index+1 ?></p>
        <form action="/App/Views/Home/Accueil.php" method="get">
            <input type="hidden" name="index" value="<?= $index+1>count($listeAlbumObjet)-1 ? $index : $index+1?>">
            <?php if ($index+1>count($listeAlbumObjet)-1) : ?>
                <button type="submit" class="disabled-button-style" disabled>Page suivante</button>
            <?php else : ?>
                <button type="submit">Page suivante</button>
            <?php endif; ?>
        </form>
    </section>
    <script src="../../../Public/JS/like.js"></script>
    <script src="../../../Public/JS/acceuil.js"></script>
    </body>
</html>