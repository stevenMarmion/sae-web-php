<?php

declare(strict_types=1);

namespace App\Views\Details;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudAlbum;
use App\Models\EntityOperations\CrudUser;
use App\Models\EntityOperations\CrudArtiste;
use App\Models\EntityOperations\CrudNote;
use Database\DatabaseConnection\ConnexionBDD;
use App\Views\Base\head;
use App\Views\Base\footer;
use App\Models\Album;

Autoloader::register();

session_start();

if (isset($_GET["id"]) && isset($_SESSION["id"])) {
    $idUser = intval($_SESSION["id"]);
    $idAlbum = intval($_GET["id"]);

    $instance = new ConnexionBDD();
    $crudAlbum = new CrudAlbum($instance::obtenir_connexion());
    $crudArtiste = new CrudArtiste($instance::obtenir_connexion());
    $crudNote = new CrudNote($instance::obtenir_connexion());

    $album = $crudAlbum->obtenirAlbumParId(intval($idAlbum));
    $img = $album["img"] == "" ? "base.jpg" : $album["img"];

    if (file_exists("../../../DataRessources/images/".$img) && (strstr($img,"%")===false)) {
        $img = $album["img"] == "" ? "base.jpg" : $album["img"];
    }
    else {
        $img = "base.jpg";
    }

    $genres="";
    $allGenres = $crudAlbum->obtenirGenresAlbum(intval($idAlbum));
    $taille=sizeof($allGenres == false ? [] : $allGenres);

    if ($taille == 0) {
        $genres.="Inconnu";
    }
    else {
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

    $compositeur = $crudArtiste->obtenirArtisteParId($crudAlbum->obtenirCompositeurId(intval($idAlbum))["idA"]);
    $interprete = $crudArtiste->obtenirArtisteParId($crudAlbum->obtenirInterpreteId(intval($idAlbum))["idA"]);

    $estNote = $crudNote->obtenirNoteUtilisateurPourAlbum($idAlbum, $idUser);
    if (is_array($estNote)) {
         $currentNote = intval($estNote["note"]);
    }
    else {
        $currentNote = 0;
    }

    $avgNotation = 0;
    $allNotationsOnAlbum = $crudNote->obtenirToutesNotesAlbum($idAlbum);
    if (is_array($allNotationsOnAlbum)) {
        $nbNotation = sizeof($allNotationsOnAlbum); // permet de savoir combien de personnes ont noté l'album en question. 
        foreach ($allNotationsOnAlbum as $noteGiven) {
            $avgNotation += intval($noteGiven["note"]);
        }
        $avgNotation /= sizeof($allNotationsOnAlbum);
    }
    else {
        // en cas d'erreurs, on affiche quand meme des données de sorte à éviter l'erreur de chargement ==> gestion d'erreurs
        $nbNotation = 0;
        $avgNotation + 0;
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
    <header>
        <?php 
            include __DIR__ . '/../Layout/Auth/NavBar.php';
        ?>
        <?php
            include __DIR__ . '/../Layout/Home/NavGenerique.php';
        ?>
    </header>
    <?php if (isset($_GET['id']) && $_GET['id']) : ?>
        <h1>
            <?=$album["titre"]?>
        </h1>
        <img src="<?= '../../../DataRessources/images/'.$img?>" alt='image album' class="imageAlbum">
        <p>
            Date de sortie : <?=$album["dateDeSortie"] ?>
        </p>
        <p>
            Compositeur(s) : <a href="DetailArtiste.php?idArtiste=<?=$compositeur["idA"]?>"><?=$compositeur["nomA"]?></a>
        </p>
        <p>
            Interprete(s) : <a href="DetailArtiste.php?idArtiste=<?=$interprete["idA"]?>"><?=$interprete["nomA"]?></a>
        </p>
        <p>
            Genre(s) : <?= $genres ?>
        </p>

        <h2>
            Notes
        </h2>
        <section class="notation-details">
            <h3>
                Note que vous donnez à : <strong><?= $album["titre"] ?></strong>
            </h3>
            <section class="action-noter">
                <form action="/App/Controllers/Details/UserNoteAlbumController.php" method="post">
                    <input type="hidden" name="album_id" value="<?= $idAlbum ?>">

                    <div class="notation-album">
                        <?php for ($i = 0 ; $i < $currentNote; ++$i) : ?>
                            <button type="submit" name="rating" value="<?= $i+1 ?>">
                                <img src="/Public/Icons/star_yellow.png" alt="star"/>
                            </button>
                        <?php endfor; ?>
                        <?php for ($i = $currentNote ; $i < 5; ++$i) : ?>
                            <button type="submit" name="rating" value="<?= $i+1 ?>">
                                <img src="/Public/Icons/star_white.png" alt="star"/>
                            </button>
                        <?php endfor; ?>
                    </div>
                </form>
            </section>

            <h3>
                Notation moyenne donnée par la communité : <strong><?= $album["titre"] ?></strong>
            </h3>
            <h4>
                Personnes qui ont noté l'album : <strong><?= $nbNotation ?></strong>
            </h4>
            <section class="avg-notation">
                <p><?= $avgNotation ?> / 5</p>
            </section>
        </section>

    <?php endif; ?>
</body>
</html>