<?php

declare(strict_types=1);

namespace App\Views\Details;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use App\Autoloader\Autoloader;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\EntityOperations\CrudAlbum;
use App\Models\EntityOperations\CrudArtiste;
use App\Models\EntityOperations\CrudComposer;
use App\Models\EntityOperations\CrudInterprete;
use App\Models\EntityOperations\CrudEtre;
use App\Models\EntityOperations\CrudFavoris;
use App\Models\EntityOperations\CrudGenre;
use App\Models\Artiste;
use App\Models\Album;
use App\Models\Genre;

Autoloader::register();

$db = new ConnexionBDD();
$crudArtiste = new CrudArtiste($db::obtenir_connexion());
$crudComposer = new CrudComposer($db::obtenir_connexion());
$crudInterpreter = new CrudInterprete($db::obtenir_connexion());
$crudAlbum = new CrudAlbum($db::obtenir_connexion());
$crudEtre = new CrudEtre($db::obtenir_connexion());
$crudFavoris = new CrudFavoris($db::obtenir_connexion());
$crudGenre = new CrudGenre($db::obtenir_connexion());

if (isset($_GET['idArtiste'])) {
    $idArtiste = intval($_GET['idArtiste']);
    $artiste = $crudArtiste->obtenirArtisteParId($idArtiste);
    $artiste = new Artiste($artiste["idA"], $artiste["nomA"]);

    $albumsComposeByArtiste = $crudComposer->obtenirAlbumdParCompositeur($idArtiste);
    $albumsInterpreteByArtiste = $crudInterpreter->obtenirAlbumsParInterprete($idArtiste);

    $nbLikeComptabilises = 0;
    $allGenresObject = [];

    $detailsAlbumsComposeObject = [];
    foreach ($albumsComposeByArtiste as $albumCompose) {

        $album = $crudAlbum->obtenirAlbumParId($albumCompose["idAl"]);
        $genres = $crudEtre->obtenirGenresParMusique($albumCompose["idAl"]);

        foreach ($genres as $genre) {
            $idGenre = $genre["idG"];
            $currentGenre = $crudGenre->obtenirGenreParId($idGenre);
            $genreObject = new Genre($currentGenre["idG"], $currentGenre["nomG"]);
            if (!ajouteGenre($allGenresObject, $genreObject)) {
                $allGenresObject[] = $genreObject;
            }
        }

        $img = verifExistanceImage($album, $album["img"]);

        $albumObject = new Album($album["id"], 
                                 $img, 
                                 $album["dateDeSortie"], 
                                 $album["titre"], 
                                 [$artiste], 
                                 [$artiste], 
                                 $allGenresObject);

        $detailsAlbumsComposeObject[] = $albumObject;

        $allLike = $crudFavoris->obtenirFavorisParAlbum($albumObject->getId()) == false ? [] : $crudFavoris->obtenirFavorisParAlbum($albumObject->getId()); 
        $nbLikeComptabilises += sizeof($allLike);
    }

    $detailsAlbumsInterpreteObject = [];
    foreach ($albumsInterpreteByArtiste as $albumInterprete) {

        $album = $crudAlbum->obtenirAlbumParId($albumInterprete["idAl"]);
        $genres = $crudEtre->obtenirGenresParMusique($albumCompose["idAl"]);

        foreach ($genres as $genre) {
            $idGenre = $genre["idG"];
            $currentGenre = $crudGenre->obtenirGenreParId($idGenre);
            $genreObject = new Genre($currentGenre["idG"], $currentGenre["nomG"]);
            if (!ajouteGenre($allGenresObject, $genreObject)) {
                $allGenresObject[] = $genreObject;
            }
        }

        $img = verifExistanceImage($album, $album["img"]);

        $albumObject = new Album($album["id"], 
                                 $img,
                                 $album["dateDeSortie"], 
                                 $album["titre"], 
                                 [$artiste], 
                                 [$artiste], 
                                 $allGenresObject);

        $detailsAlbumsInterpreteObject[] = $albumObject;

        $allLike = $crudFavoris->obtenirFavorisParAlbum($albumObject->getId()) == false ? [] : $crudFavoris->obtenirFavorisParAlbum($albumObject->getId()); 
        $nbLikeComptabilises += sizeof($allLike);
    }
}

function verifExistanceImage($album, $img) {
    if(file_exists(__DIR__ . "/../../../DataRessources/images/" . $img) && (strstr($img??"","%")===false)) {
        $img = $album["img"] == "" ? "base.jpg" : $album["img"];
    }
    else {
        $img = "base.jpg";
    }
    return $img;
}

function ajouteGenre($allGenresObject, $genreObject) {
    $present = false;
    foreach ($allGenresObject as $genre) {
        if ($genre->equals($genreObject)) {
            $present = true;
        }
    }
    return $present;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Details/details-artistes-style.css">
    <title>Details - Artiste</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../Layout/Auth/NavBar.php';
    ?>
    <?php
        include __DIR__ . '/../Layout/Home/NavGenerique.php';
    ?>
    <?php if (isset($artiste)): ?>
        <div class="artiste-details">
            <h2>
                Découvrez qui est <strong><?= $artiste->getNomArtiste() ?></strong>
            </h2>
        </div>
    <?php endif; ?>

    <h2>Nombre de like comptabilisés</h2>
    <div class="nombre-like">
        <p>
            Sur CampusGroove, <?= $artiste->getNomArtiste() ?> à comptabilisé(e) <strong><?= $nbLikeComptabilises ?></strong> like !
        </p>
    </div>

    <h2><?= $artiste->getNomArtiste() ?> et sa popularité </h2>
    <div class="text-transition">
    <p>
        Découvrez quels albums <strong><?= $artiste->getNomArtiste() ?></strong> a composés et interprétés au fil de sa carrière. 
        Explorez son répertoire musical varié, des premières compositions aux dernières interprétations. 
        Plongez dans l'univers artistique de <?= $artiste->getNomArtiste() ?> et laissez-vous emporter par sa créativité et son talent.
        Par ailleurs, découvrez <?= $artiste->getNomArtiste() ?> et toutes ces facettes puisqu'il travaille dans diverses genres comme 
        <?php foreach ($allGenresObject as $indexGenre => $genre): ?>
            <strong id="genre">
                <?= $genre->getNomGenre() ?>
                <?php if (($indexGenre+1) == sizeof($allGenresObject)) : ?>
                    !
                <?php else : ?>
                    ,
                <?php endif; ?>
            </strong>
        <?php endforeach; ?>
    </p>

    </div>

    <?php if (!empty($detailsAlbumsComposeObject)): ?>
        <h2>Albums composés par <?= $artiste->getNomArtiste() ?></h2>
        <div class="albums">
            <?php foreach ($detailsAlbumsComposeObject as $albumObject): ?>
                <div class="album">
                    <h3>
                        <?= $albumObject->getTitre() ?>
                    </h3>
                    <img src="../../../DataRessources/images/<?= $albumObject->getImg() ?>" alt="image de l'album">
                    <p>
                        Année de sortie: <?= $albumObject->getDateSortie() ?>
                    </p>
                    <a href='/App/Views/Details/DetailAlbum.php?id= <?= $albumObject->getId() ?>'>
                        <button>voir plus</button>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($detailsAlbumsInterpreteObject)): ?>
        <h2>Albums interprétés par <?= $artiste->getNomArtiste() ?></h2>
        <div class="albums">
            <?php foreach ($detailsAlbumsInterpreteObject as $albumObject): ?>
                <div class="album">
                    <h3>
                        <?= $albumObject->getTitre() ?>
                    </h3>
                    <img src="../../../DataRessources/images/<?= $albumObject->getImg() ?>" alt="image de l'album">
                    <p>
                        Année de sortie: <?= $albumObject->getDateSortie() ?>
                    </p>
                    <a href='/App/Views/Details/DetailAlbum.php?id= <?= $albumObject->getId() ?>'>
                        <button>voir plus</button>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($detailsAlbumsComposeObject) && empty($detailsAlbumsInterpreteObject)): ?>
        <p>Aucun album trouvé pour cet artiste.</p>
    <?php endif; ?>
</body>
</html>