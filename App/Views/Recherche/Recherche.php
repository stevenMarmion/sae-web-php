<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudAlbum;
use App\Models\EntityOperations\CrudArtiste;
use App\Models\EntityOperations\CrudPlaylist;
use App\Models\Album;
use Database\DatabaseConnection\ConnexionBDD;

Autoloader::register();

session_start();
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
    <title>Recherche</title>
</head>
<body>
    <form action="/App/Views/Recherche/Recherche.php" method="post">
        <input type="text" id="recherche" name="recherche" placeholder="recherche" required><br>
        Recherche par : <br>
        <select name="filtre">
            <option value="nomAlbum">Nom d'album</option>
            <option value="nomCompositeur">Nom du compositeur</option>
            <option value="nomInterprete">Nom de l'interprete</option>
            <option value="genre">Genre</option>
            <option value="Annee">Année</option>
        </select><br>
        <input type="submit" value="rechercher">
    </form>
    <script src="/Public/JS/recherche.js"></script>
<?php

function AlbumToAlbumObjet(array $listeAlbum, ConnexionBDD $instance,CrudAlbum $crudAlbum, CrudArtiste $crudArtiste){
    $listeAlbumObjet=[];
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
    return $listeAlbumObjet;
}

function rechercheNomAlbum(string $nomAlbum, CrudAlbum $crudAlbum, ConnexionBDD $instance, CrudArtiste $crudArtiste) {
    $listeAlbum = $crudAlbum->obtenirAlbumParNom($nomAlbum);
    return AlbumToAlbumObjet($listeAlbum, $instance, $crudAlbum, $crudArtiste);
}

function rechercheNomCompositeur(string $nomArtiste, CrudAlbum $crudAlbum, ConnexionBDD $instance, CrudArtiste $crudArtiste){
    $listeAlbum = $crudAlbum->obtenirAlbumParCompositeur($nomArtiste);
    return AlbumToAlbumObjet($listeAlbum, $instance, $crudAlbum, $crudArtiste);
}

function rechercheNomInterprete(string $nomArtiste, CrudAlbum $crudAlbum, ConnexionBDD $instance, CrudArtiste $crudArtiste){
    $listeAlbum = $crudAlbum->obtenirAlbumParInterprete($nomArtiste);
    return AlbumToAlbumObjet($listeAlbum, $instance, $crudAlbum, $crudArtiste);
}

function rechercheAnnee(string $annee, CrudAlbum $crudAlbum, ConnexionBDD $instance, CrudArtiste $crudArtiste){
    $listeAlbum = $crudAlbum->obtenirAlbumParAnnee($annee);
    return AlbumToAlbumObjet($listeAlbum, $instance, $crudAlbum, $crudArtiste);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $instance = new ConnexionBDD();
    $crudAlbum = new CrudAlbum($instance::obtenir_connexion());
    $crudArtiste = new CrudArtiste($instance::obtenir_connexion());
    $crudPlaylist = new CrudPlaylist($instance::obtenir_connexion());
    $listeAlbumObjet=[];
    if (isset($_POST['filtre']) && isset($_POST['recherche']) && $_POST['recherche']!="") {
        if($_POST["filtre"]=="nomAlbum"){
            $listeAlbumObjet = rechercheNomAlbum($_POST['recherche'],$crudAlbum,$instance,$crudArtiste);
        }
        else if($_POST["filtre"]=="nomCompositeur"){
            $listeAlbumObjet = rechercheNomCompositeur($_POST['recherche'],$crudAlbum,$instance,$crudArtiste);
        }
        else if($_POST["filtre"]=="nomInterprete"){
            $listeAlbumObjet = rechercheNomInterprete($_POST['recherche'],$crudAlbum,$instance,$crudArtiste);
        }
        else if($_POST["filtre"]=="Annee"){
            $listeAlbumObjet = rechercheAnnee($_POST['recherche'],$crudAlbum,$instance,$crudArtiste);
        }

    }

?>

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
        ?>
    </ul>
    <?php
}?>

</body>