<?php

require_once __DIR__ . '/../../../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudAlbum;
use \App\Models\Album;

Autoloader::register();

$db = new ConnexionBDD();
$crudAlbum = new CrudAlbum($db::obtenir_connexion());

if (isset($_SERVER["REQUEST_METHOD"]) && isset($_GET["delete"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tableToUpdate = $_GET["delete"]; // ALBUMS
        if ($tableToUpdate === "ALBUMS") {
            $albumId = $_POST["album_id"];
            $listeAlbum = $crudAlbum->obtenirAlbumParId($albumId);
            if ($listeAlbum != false) {
                $album = new Album($listeAlbum["id"], $listeAlbum["img"], $listeAlbum["dateDeSortie"], $listeAlbum["titre"], [], [], []);
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
    <title>Delete - Admin - Album</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../../../Layout/Admin/AdminNavBar.php';
    ?>
    <h1>Suppression d'un album</h1>

    <form action="/App/Controllers/Admin/AdminDeleteController.php?delete=ALBUMS" method="post">
    <input type="hidden" id="album_id" name="album_id" value="<?= $album->getId() ?>">
        
        <p class="delete-warning">
            Etes-vous sur de vouloir supprimer l'album "<?= $album->getTitre() ?>" ?
        </p>
        <p class="informations-warning">
            Si vous supprimez cet album, toutes les utilisateurs n'y auront plus accès et toutes données relatives à cet album seront supprimés !
        </p>

        <div class="choice-delete">
            <button type="submit">Supprimer</button>
            <button type="button">
                <a href="/App/Views/Admin/Details/PanelDetails.php?table=ALBUMS">
                    Annuler
                </a>
            </button>
        </div>
    </form>

</body>
</html>
