<?php

require_once __DIR__ . '/../../../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudArtiste;
use \App\Models\Artiste;

Autoloader::register();

$db = new ConnexionBDD();
$crudArtiste = new CrudArtiste($db::obtenir_connexion());

if (isset($_SERVER["REQUEST_METHOD"]) && isset($_GET["delete"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tableToUpdate = $_GET["delete"]; // UTILISATEUR
        if ($tableToUpdate === "ARTISTES") {
            $artisteId = $_POST["artiste_id"];
            $artiste = $crudArtiste->obtenirArtisteParId($artisteId);
            if ($artiste != false) {
                $currentArtiste = new Artiste($artiste["idA"], $artiste["nomA"]);
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
    <title>Delete - Admin - Artiste</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../../../Layout/Admin/AdminNavBar.php';
    ?>
    <h1>Suppression d'artiste</h1>

    <form action="/App/Controllers/Admin/AdminDeleteController.php?delete=ARTISTES" method="post">
        <input type="hidden" id="artiste_id" name="artiste_id" value="<?= $currentArtiste->getId() ?>">
        
        <p class="delete-warning">
            Etes-vous sur de vouloir supprimer l'artiste "<?= $currentArtiste->getNomArtiste() ?>" ?
        </p>
        <p class="informations-warning">
            Si vous supprimez cet artiste, vous ne pourrez plus l'associer à des albums ni consulter ces albums !
        </p>

        <div class="choice-delete">
            <button type="submit">Supprimer</button>
            <button type="button">
                <a href="/App/Views/Admin/Details/PanelDetails.php?table=ARTISTES">
                    Annuler
                </a>
            </button>
        </div>
    </form>

</body>
</html>
