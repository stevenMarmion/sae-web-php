<?php


namespace App\Views\Admin\Details;

require_once __DIR__ . '/../../../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudArtiste;
use \App\Models\Artiste;

Autoloader::register();

$db = new ConnexionBDD();
$crudArtiste = new CrudArtiste($db::obtenir_connexion());

if (isset($_SERVER["REQUEST_METHOD"]) && isset($_GET["update"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tableToUpdate = $_GET["update"]; // UTILISATEUR ou ALBUMS
        if ($tableToUpdate === "ARTISTES") {
            $artisteId = $_POST["artiste_id"] ?? null;
            if (!empty($artisteId)) {
                $artiste = $crudArtiste->obtenirArtisteParId($artisteId);
                $currentArtiste = new Artiste($artiste["idA"], $artiste["nomA"]);
            }
        }
    }
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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Admin/Details/panel-admin-update-user-style.css">
    <title>Update - Admin - Artiste</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../../../Layout/Admin/AdminNavBar.php';
    ?>
    <section>
        <h1>Modifier les détails de l'artiste</h1>

        <?php if ($errorDetected === null) : ?>
        <form action="/App/Controllers/Admin/AdminUpdateController.php?update=ARTISTES" method="post">
            <input type="hidden" name="artiste_id" value="<?= $currentArtiste->getId() ?>">

            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?= $currentArtiste->getNomArtiste() ?>" required>
            </div>

            <input type="submit" value="Mettre à jour">
        </form>
        <?php endif; ?>
        <?php if ($errorDetected) : ?>
            <p class="erreur-add">
                Le nom de cet artiste existe déjà, veuillez en choisir un autre...
            </p>
        <?php endif; ?>
    </section>
</body>
</html>