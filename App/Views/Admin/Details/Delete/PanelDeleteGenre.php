<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudGenre;
use \App\Models\Genre;

Autoloader::register();

$db = new ConnexionBDD();
$crudGenre = new CrudGenre($db::obtenir_connexion());

if (isset($_SERVER["REQUEST_METHOD"]) && isset($_GET["delete"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tableToUpdate = $_GET["delete"]; // UTILISATEUR
        if ($tableToUpdate === "GENRES") {
            $genreId = intval($_POST["genre_id"]) ?? null;
            $genre = $crudGenre->obtenirGenreParId($genreId);
            if ($genre != false) {
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
    <link rel="stylesheet" href="/Public/Css/Admin/Details/panel-admin-delete-style.css">
    <title>Delete - Admin - Genre</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../../../Layout/Admin/AdminNavBar.php';
    ?>
    <h1>Suppression de genre</h1>

    <form action="/App/Controllers/Admin/AdminDeleteController.php?delete=GENRES" method="post">
        <input type="hidden" id="genre_id" name="genre_id" value="<?= $currentGenre->getId() ?>">
        
        <p class="delete-warning">
            Etes-vous sur de vouloir supprimer le genre "<?= $currentGenre->getNomGenre() ?>" ?
        </p>
        <p class="informations-warning">
            Si vous supprimez ce genre, tous les albums liés à ce genre seront supprimés !
        </p>

        <div class="choice-delete">
            <button type="submit">Supprimer</button>
            <button type="button">
                <a href="/App/Views/Admin/Details/PanelDetails.php?table=GENRES">
                    Annuler
                </a>
            </button>
        </div>
    </form>

</body>
</html>
