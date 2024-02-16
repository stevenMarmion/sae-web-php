<?php

declare(strict_types=1);

namespace App\Views\Details;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use App\Autoloader\Autoloader;
use App\Models\Album;
use App\Models\EntityOperations\CrudAlbum;
use App\Models\EntityOperations\CrudArtiste;
use App\Models\EntityOperations\CrudContenir;
use App\Models\EntityOperations\CrudPlaylist;
use Database\DatabaseConnection\ConnexionBDD;


Autoloader::register();

session_start();

if (isset($_SESSION['id']) && isset($_GET['idP'])) {
    $idP = intval($_GET['idP']); // l'id de la playlist 
    $idUser = intval($_SESSION['id']); // l'id de l'utilisateur actuel
    $db = new ConnexionBDD();
    $crudContenir = new CrudContenir($db::obtenir_connexion());
    $allBumsInPlaylist = $crudContenir->obtenirTousAlbumsPourPlaylist($idP);
    $crudPlaylist = new CrudPlaylist($db::obtenir_connexion());
    $nomPlaylist = $crudPlaylist->obtenirPlaylistParId($idP)["nomPlaylist"];

    $listeAlbumObjet = [];

    if (is_array($allBumsInPlaylist)) {
        $crudAlbum = new CrudAlbum($db::obtenir_connexion());
        $crudArtiste = new CrudArtiste($db::obtenir_connexion());
        foreach ($allBumsInPlaylist as $album) {
            $album = $crudAlbum->obtenirAlbumParId(intval($album['idAl']));
            $idC = $crudAlbum->obtenirCompositeurId(intval($album["id"]))["idA"];
            $idI = $crudAlbum->obtenirInterpreteId(intval($album["id"]))["idA"];
            $listeGenre = $crudAlbum->obtenirGenresAlbum(intval($album["id"]));

            $img = $album["img"] ?? "";
            if(file_exists("../../../DataRessources/images/".$img) && (strstr($img,"%")===false)){
                $img = $album["img"] == "" ? "base.jpg" : $album["img"];
            }
            else{
                $img = "base.jpg";
            }

            $al = new Album($idM=intval($album["id"]),
                            $img,
                            $dateDeSortie=intval($album["dateDeSortie"]),
                            $title=$album["titre"],
                            $compositeur=$crudArtiste->obtenirArtisteParId(intval($idC)) ?: [],
                            $interprete=$crudArtiste->obtenirArtisteParId(intval($idI)) ?: [],
                            $genre = $listeGenre == false ? [] : $listeGenre,
                        );
            array_push($listeAlbumObjet, $al);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Details/details-playlist-style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script>
    <script src="/Public/JS/like.js" defer></script>
    <script src="/Public/JS/verifLike.js" defer></script>
    <title>Playlist - Consultation</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../Layout/Auth/NavBar.php'; 
    ?>
    <?php 
        include __DIR__ . '/../Layout/Home/NavGenerique.php'; 
    ?>
    <h1>Playlists</h1>

    <div class="playlist">
        <h2>Albums dans la playlist <?= $nomPlaylist ?></h2>
        <ul>
            <?php foreach ($listeAlbumObjet as $album) : ?>
                <li class="album">
                    <img src="<?= '../../../DataRessources/images/'. $album->getImg() ?>" alt="image album" class="imageAlbum">
                    <h3><?= $album->getTitre() ?></h3>
                    <p>Compositeur(s) : <?= $album->getCompositeurs()["nomA"] ?></p>
                    <p>Interprète(s) : <?= $album->getInterpretes()["nomA"] ?></p>
                    <p>
                        Genres : 
                        <?php foreach($album->getGenres() as $indexGenre => $genre) : ?>
                            <?php if ($indexGenre == sizeof($album->getGenres()) -1) : ?>
                                <?= $genre["nomG"] ?> !
                            <?php else : ?>
                                <?= $genre["nomG"] ?>, 
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </p>
                    <a href='/App/Views/Details/DetailAlbum.php?id=<?= $album->getId() ?>'>
                        <button>voir plus</button>
                    </a>
                    <div class="retirer-album">
                        <form action="/App/Controllers/Playlist/DeleteAlbumFromPlaylistController.php" method="post">
                            <input type="hidden" id="idAl" name="idAl" value="<?= $album->getId() ?>">
                            <input type="hidden" id="idP" name="idP" value="<?= $idP ?>">
                            <button type="submit">Retirer</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
