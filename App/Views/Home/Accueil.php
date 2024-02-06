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
    <input type="checkbox" id="checkbox" onclick="header('Location: /App/Views/playlist/Playlists.php')"/>
    <label for="checkbox">
      <svg id="heart-svg" viewBox="467 392 58 57" xmlns="http://www.w3.org/2000/svg">
        <g id="Group" fill="none" fill-rule="evenodd" transform="translate(467 392)">
          <path d="M29.144 20.773c-.063-.13-4.227-8.67-11.44-2.59C7.63 28.795 28.94 43.256 29.143 43.394c.204-.138 21.513-14.6 11.44-25.213-7.214-6.08-11.377 2.46-11.44 2.59z" id="heart" fill="#AAB8C2"/>
          <circle id="main-circ" fill="#E2264D" opacity="0" cx="29.5" cy="29.5" r="1.5"/>

          <g id="grp7" opacity="0" transform="translate(7 6)">
            <circle id="oval1" fill="#9CD8C3" cx="2" cy="6" r="2"/>
            <circle id="oval2" fill="#8CE8C3" cx="5" cy="2" r="2"/>
          </g>

          <g id="grp6" opacity="0" transform="translate(0 28)">
            <circle id="oval1" fill="#CC8EF5" cx="2" cy="7" r="2"/>
            <circle id="oval2" fill="#91D2FA" cx="3" cy="2" r="2"/>
          </g>

          <g id="grp3" opacity="0" transform="translate(52 28)">
            <circle id="oval2" fill="#9CD8C3" cx="2" cy="7" r="2"/>
            <circle id="oval1" fill="#8CE8C3" cx="4" cy="2" r="2"/>
          </g>

          <g id="grp2" opacity="0" transform="translate(44 6)">
            <circle id="oval2" fill="#CC8EF5" cx="5" cy="6" r="2"/>
            <circle id="oval1" fill="#CC8EF5" cx="2" cy="2" r="2"/>
          </g>

          <g id="grp5" opacity="0" transform="translate(14 50)">
            <circle id="oval1" fill="#91D2FA" cx="6" cy="5" r="2"/>
            <circle id="oval2" fill="#91D2FA" cx="2" cy="2" r="2"/>
          </g>

          <g id="grp4" opacity="0" transform="translate(35 50)">
            <circle id="oval1" fill="#F48EA7" cx="6" cy="5" r="2"/>
            <circle id="oval2" fill="#F48EA7" cx="2" cy="2" r="2"/>
          </g>

          <g id="grp1" opacity="0" transform="translate(24)">
            <circle id="oval1" fill="#9FC7FA" cx="2.5" cy="3" r="2"/>
            <circle id="oval2" fill="#9FC7FA" cx="7.5" cy="2" r="2"/>
          </g>
        </g>
      </svg>
    </label>
  </div>
</div>
        
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