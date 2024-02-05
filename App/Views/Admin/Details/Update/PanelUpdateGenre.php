<?php


namespace App\Views\Admin\Details;

require_once __DIR__ . '/../../../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudGenre;
use \App\Models\Genre;

Autoloader::register();

$db = new ConnexionBDD();
$crudGenre = new CrudGenre($db::obtenir_connexion());

if (isset($_SERVER["REQUEST_METHOD"]) && isset($_GET["update"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tableToUpdate = $_GET["update"]; // UTILISATEUR ou ALBUMS
        if ($tableToUpdate === "GENRES") {
            $genreId = $_POST["genre_id"] ?? null;
            if (!empty($genreId)) {
                $genre = $crudGenre->obtenirGenreParId($genreId);
                $currentGenre = new Genre($genre["idG"], $genre["nomG"]);
            }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Admin/Details/panel-admin-update-user-style.css">
    <title>Update - Admin - Genre</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../../../Layout/Admin/AdminNavBar.php';
    ?>
    <section>
        <h1>Modifier les détails du genre</h1>

        <form action="/App/Controllers/Admin/AdminUpdateController.php?update=GENRES" method="post">
            <input type="hidden" name="genre_id" value="<?= $currentGenre->getId() ?>">

            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?= $currentGenre->getNomGenre() ?>" required>
            </div>

            <input type="submit" value="Mettre à jour">
        </form>
        <?php if ($errorDetected) : ?>
            <p class="erreur-add">
                Ce nom de genre existe déjà, veuillez en choisir un autre...
            </p>
        <?php endif; ?>
    </section>
</body>
</html>