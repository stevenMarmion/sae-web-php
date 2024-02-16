<?php

declare(strict_types=1);

namespace App\Views\Admin\Details\Add;

require_once __DIR__ . '/../../../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudAlbum;
use \App\Models\EntityOperations\CrudComposer;
use \App\Models\EntityOperations\CrudInterprete;
use \App\Models\EntityOperations\CrudArtiste;
use \App\Models\EntityOperations\CrudGenre;
use \App\Models\EntityOperations\CrudEtre;
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

$allGenres = $crudGenre->obtenirTousGenres();
$allGenresObject = [];
foreach ($allGenres as $genre) {
    $genre = new Genre($genre['idG'],$genre['nomG']);
    $allGenresObject[] = $genre;
}

$allArtistes = $crudArtiste->obtenirTousArtistes();
$allArtistesObject = [];
foreach ($allArtistes as $artiste) {
    $artiste = new Artiste($artiste['idA'],$artiste['nomA']);
    $allArtistesObject[] = $artiste;
}

$errorDetected = null;

if (isset($_GET['error'])) {
    if ($_GET["error"] == "AlreadyExists") {
        $errorDetected = true;
    }
    else {
        $errorDetected = false;
    }
}

$nbComp = 1;
$nbInt = 1;
$nbGenres = 1;

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Admin/Details/panel-admin-update-album-style.css">
    <title>Add - Admin - Album</title>
</head>

<body>
    <?php 
        include __DIR__ . '/../../../Layout/Admin/AdminNavBar.php';
    ?>
    <section>
        <h1>Modifier les détails de l'album</h1>

        <form action="/App/Controllers/Admin/AdminAddController.php?add=ALBUMS" method="post">
            <div class="form-group">
                <label for="album_id">Identifiant :</label>
                <input type="text" name="album_id" required>
            </div>

            <div class="form-group">
                <label for="album_img">Image associée :</label>
                <input type="file" accept="image/*" name="album_img" required>
            </div>

            <div class="form-group">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" required>
            </div>

            <div class="form-group">
                <label for="dateDeSortie">Date de sortie :</label>
                <input type="text" id="dateDeSortie" name="dateDeSortie" required>
            </div>

            <div class="form-group">
                <label for="compositeurs">Compositeurs :</label>
                <select class="select-compositeurs" id="tous-compositeurs-<?=$nbComp?>" name="tous-compositeurs-<?=$nbComp?>" required>
                    <?php foreach ($allArtistesObject as $currentCompositeurs): ?>
                        <option value="<?= $currentCompositeurs->getId() ?>">
                            <?= $currentCompositeurs->getNomArtiste() ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="nb-compositeurs" value="<?=$nbComp?>" required>
            </div>

            <div class="form-group">
                <label for="interpretes">Interpretes :</label>
                <select class="select-interpretes" id="tous-interpretes-<?=$nbInt?>" name="tous-interpretes-<?=$nbInt?>" required>
                    <?php foreach ($allArtistesObject as $currentInterpretes): ?>
                        <option value="<?= $currentInterpretes->getId() ?>">
                            <?= $currentInterpretes->getNomArtiste() ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="nb-interpretes" value="<?=$nbInt?>" required>
            </div>

            <div class="form-group">
                <label for="genre">Genres :</label>
                <select class="select-genres" id="tous-genres-<?=$nbGenres?>" name="tous-genres-<?=$nbGenres?>" required>
                    <?php foreach ($allGenresObject as $currentGenre): ?>
                        <option value="<?= $currentGenre->getId() ?>">
                            <?= $currentGenre->getNomGenre() ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="nb-genres" value="<?=$nbGenres?>" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Ajouter">
            </div>
        </form>
        <?php if ($errorDetected) : ?>
            <p class="erreur-add">
                L'id de cet album existe déjà, veuillez en choisir un autre...
            </p>
        <?php endif; ?>
    </section>
</body>

</html>
