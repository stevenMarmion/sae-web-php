<?php

namespace App\Views\Admin\Details;

require_once __DIR__ . '/../../../../Autoloader/autoloader.php';

// Tous ces require sont temporaire, comprendre pourquoi l'Autoloader ne fonctionne pas...
require_once __DIR__ . '/../../../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once __DIR__ . '/../../../../Models/EntityOperations/CrudAlbum.php';
require_once __DIR__ .'/../../../../Models/EntityOperations/CrudComposer.php';
require_once __DIR__ .'/../../../../Models/EntityOperations/CrudInterprete.php';
require_once __DIR__ .'/../../../../Models/EntityOperations/CrudArtiste.php';
require_once __DIR__ .'/../../../../Models/EntityOperations/CrudEtre.php';
require_once __DIR__ .'/../../../../Models/EntityOperations/CrudGenre.php';
require_once __DIR__ .'/../../../../Models/Album.php';
require_once __DIR__ .'/../../../../Models/Artiste.php';
require_once __DIR__ .'/../../../../Models/Genre.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudAlbum;
use \App\Models\EntityOperations\CrudComposer;
use \App\Models\EntityOperations\CrudInterprete;
use \App\Models\EntityOperations\CrudArtiste;
use \App\Models\EntityOperations\CrudGenre;
use \App\Models\EntityOperations\CrudEtre;
use \App\Models\Album;
use \App\Models\Artiste;
use \App\Models\Genre;

Autoloader::register();

$db = new ConnexionBDD();
$crudInterpreter = new CrudInterprete($db::obtenir_connexion());
$crudComposer = new CrudComposer($db::obtenir_connexion());
$crudArtiste = new CrudArtiste($db::obtenir_connexion());
$crudGenre = new CrudGenre($db::obtenir_connexion());
$crudEtre = new CrudEtre($db::obtenir_connexion());
$crudAlbum = new CrudAlbum($db::obtenir_connexion());

if (isset($_SERVER["REQUEST_METHOD"]) && isset($_GET["update"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tableToUpdate = $_GET["update"]; // UTILISATEUR ou ALBUMS
        if ($tableToUpdate === "ALBUMS") {
            $albumId = $_POST["album_id"] ?? null;
            if (!empty($albumId)) {

                $album = $crudAlbum->obtenirAlbumParId($albumId);
                $albumRecupObject = [];
                $currentAlbum = new Album($album["id"],$album["img"] ?? "",$album["dateDeSortie"],$album["titre"],[],[],[]);

                // Récupérer les compositeurs
                $compositeurs = $crudComposer->obtenirCompositeursParAlbum($album["id"]);
                foreach ($compositeurs as $compositeur) {
                    $currentArtiste = $crudArtiste->obtenirArtisteParId($compositeur["idA"]);
                    $currentCompositeur = new Artiste($compositeur["idA"],$currentArtiste['nomA']);
                    $currentAlbum->ajouterCompositeur($currentCompositeur);
                }

                // Récupérer les interprètes
                $interpretes = $crudInterpreter->obtenirInterpretesParAlbum($album["id"]);
                foreach ($interpretes as $interprete) {
                    $currentArtiste = $crudArtiste->obtenirArtisteParId($interprete["idA"]);
                    $currentInterprete = new Artiste($interprete["idA"],$currentArtiste['nomA']);
                    $currentAlbum->ajouterInterprete($currentInterprete);
                }

                // Récupérer les genres
                $genres = $crudEtre->obtenirGenresParMusique($album["id"]);
                foreach ($genres as $genre) {
                    $currentGenre = $crudGenre->obtenirGenreParId($genre["idG"]);
                    $currentGenre = new Genre($genre["idG"],$currentGenre['nomG']);
                    $currentAlbum->ajouterGenre($currentGenre);
                }
                $albumRecupObject[] = $currentAlbum;
            }
            if (!empty($albumRecupObject)) {
                $album = $albumRecupObject[0];
            }
        }
    }
}

$allGenres = $crudGenre->obtenirTousGenres();
$allGenresObject = [];
foreach ($allGenres as $genre) {
    $genre = new Genre($genre['idG'],$genre['nomG']);
    $allGenresObject[] = $genre;
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Admin/Details/panel-admin-update-album-style.css">
    <title>Update - Admin - Album</title>
</head>

<body>
    <?php 
        include __DIR__ . '/../../../Layout/Admin/AdminNavBar.php';
    ?>
    <section>
        <h1>Modifier les détails de l'album</h1>

        <form action="/App/Controllers/Admin/AdminUpdateController.php?update=ALBUMS" method="post">
            <input type="hidden" name="album_id" value="<?= $album->getId() ?>">
            <input type="hidden" name="album_img" value="<?= $album->getImg() ?>">

            <div class="form-group">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" value="<?= $album->getTitre() ?>" required>
            </div>

            <div class="form-group">
                <label for="dateDeSortie">Date de sortie :</label>
                <input type="text" id="dateDeSortie" name="dateDeSortie" value="<?= $album->getDateSortie() ?>" required>
            </div>

            <div class="form-group">
                <label for="compositeurs">Compositeurs :</label>
                <?php foreach ($album->getCompositeurs() as $index => $compositeur): ?>
                    <input type="hidden" name="ancien-compositeurs-<?=$index?>" value="<?= $compositeur->getId() ?>">
                    <input type="text" id="compositeurs-<?=$index?>" name="compositeurs-<?=$index?>" value="<?= $compositeur->getNomArtiste() ?>" required>
                <?php endforeach; ?>
                <input type="hidden" name="nb-compositeurs" value="<?= sizeof($album->getCompositeurs()) ?>">
            </div>

            <div class="form-group">
                <label for="interprete">Interpretes :</label>
                <?php foreach ($album->getInterpretes() as $index => $interprete): ?>
                    <input type="hidden" name="ancien-interpretes-<?=$index?>" value="<?= $compositeur->getId() ?>">
                    <input type="text" id="interpretes-<?=$index?>" name="interpretes-<?=$index?>" value="<?= $interprete->getNomArtiste() ?>" required>
                <?php endforeach; ?>
                <input type="hidden" name="nb-interpretes" value="<?= sizeof($album->getInterpretes()) ?>">
            </div>

            <div class="form-group">
                <label for="genre">Genres :</label>
                <?php foreach ($album->getGenres() as $index => $genre): ?>
                    <input type="hidden" name="ancien-genres-<?=$index?>" value="<?= $genre->getId() ?>">

                    <select class="select-genres" id="tous-genres-<?=$index?>" name="tous-genres-<?=$index?>" required>
                    <?php foreach ($allGenresObject as $currentGenre): ?>
                        <option value="<?= $currentGenre->getId() ?>" <?= ($currentGenre->getId() == $album->getGenres()[$index]->getId()) ? 'selected' : '' ?>>
                            <?= $currentGenre->getNomGenre() ?>
                        </option>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <input type="hidden" name="nb-genres" value="<?= sizeof($album->getGenres()) ?>">
            </div>

            <div class="form-group">
                <input type="submit" value="Mettre à jour">
            </div>
        </form>
    </section>
</body>

</html>
