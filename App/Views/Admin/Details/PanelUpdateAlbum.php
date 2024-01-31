<?php

namespace App\Views\Admin\Details;

require_once __DIR__ . '/../../../Autoloader/autoloader.php';

// Tous ces require sont temporaire, comprendre pourquoi l'Autoloader ne fonctionne pas...
require_once __DIR__ . '/../../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once __DIR__ . '/../../../Models/EntityOperations/CrudAlbum.php';
require_once __DIR__ .'/../../../Models/EntityOperations/CrudComposer.php';
require_once __DIR__ .'/../../../Models/EntityOperations/CrudInterprete.php';
require_once __DIR__ .'/../../../Models/EntityOperations/CrudArtiste.php';
require_once __DIR__ .'/../../../Models/EntityOperations/CrudEtre.php';
require_once __DIR__ .'/../../../Models/EntityOperations/CrudGenre.php';
require_once __DIR__ .'/../../../Models/Album.php';
require_once __DIR__ .'/../../../Models/Artiste.php';
require_once __DIR__ .'/../../../Models/Genre.php';

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

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Admin/Details/panel-admin-update-album-style.css">
    <title>Modifier les détails de l'album</title>
</head>

<body>
    <?php 
        include __DIR__ . '/../../Layout/Admin/AdminNavBar.php';
    ?>
    <section>
        <h1>Modifier les détails de l'album</h1>

        <form action="/App/Controllers/Admin/AdminUpdateController.php?update=ALBUMS" method="post">
            <input type="hidden" name="album_id" value="<?= $album->getId() ?>">

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
                <?php foreach ($album->getCompositeurs() as $compositeur): ?>
                    <input type="text" id="compositeurs" name="compositeurs[]" value="<?= $compositeur->getNomArtiste() ?>" required>
                <?php endforeach; ?>
            </div>

            <div class="form-group">
                <label for="interprete">Interpretes :</label>
                <?php foreach ($album->getInterpretes() as $interprete): ?>
                    <input type="text" id="interprete" name="interprete" value="<?= $interprete->getNomArtiste() ?>" required>
                <?php endforeach; ?>
            </div>

            <div class="form-group">
                <label for="genre">Genres :</label>
                <?php foreach ($album->getGenres() as $genre): ?>
                    <input type="text" id="genre" name="genre" value="<?= $genre->getNomGenre() ?>" required>
                <?php endforeach; ?>
            </div>

            <div class="form-group">
                <input type="submit" value="Mettre à jour">
            </div>
        </form>
    </section>
</body>

</html>
