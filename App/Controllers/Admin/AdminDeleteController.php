<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudGenre;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudAlbum;
use \App\Models\EntityOperations\CrudUser;
use \App\Models\EntityOperations\CrudArtiste;

Autoloader::register();

$db = new ConnexionBDD();
$crudUser = new CrudUser($db::obtenir_connexion());
$crudAlbum = new CrudAlbum($db::obtenir_connexion());
$crudArtiste = new CrudArtiste($db::obtenir_connexion());
$crudGenre = new CrudGenre($db::obtenir_connexion());

if (isset($_SERVER["REQUEST_METHOD"]) && isset($_GET["delete"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tableToUpdate = $_GET["delete"]; // UTILISATEUR ou ALBUMS
        switch ($tableToUpdate) {
            case "UTILISATEUR": 
                $userId = intval($_POST["user_id"]);

                $actionValidDeleteOnTableUser = $crudUser->supprimerUtilisateur($userId);

                if ($actionValidDeleteOnTableUser) {
                    header('Location: /App/Views/Admin/Details/PanelDetails.php?table=UTILISATEUR'); // redirection vers la page actuelle actualisée
                    exit();
                }
                else {
                    header('Location: /App/Views/Admin/Details/PanelDetails.php?error=AlreadyExists'); // redirection vers la page actuelle avec erreurs
                    exit();
                }

            case "ALBUMS": 
                $albumId = intval($_POST["album_id"]);

                $actionValidDeleteOnTableAlbum = $crudAlbum->supprimerAlbum($albumId);

                if ($actionValidDeleteOnTableAlbum) {
                    header('Location: /App/Views/Admin/Details/PanelDetails.php?table=ALBUMS'); // redirection vers la page actuelle actualisée
                    exit();
                }
                else {
                    header('Location: /App/Views/Admin/Details/PanelDetails.php?error=AlreadyExists'); // redirection vers la page actuelle avec erreurs
                    exit();
                }

            case "ARTISTES": 
                $artisteId = intval($_POST["artiste_id"]);

                $actionValidDeleteOnTableArtiste = $crudArtiste->supprimerArtiste($artisteId);

                if ($actionValidDeleteOnTableArtiste) {
                    header('Location: /App/Views/Admin/Details/PanelDetails.php?table=ARTISTES'); // redirection vers la page actuelle actualisée
                    exit();
                }
                else {
                    header('Location: /App/Views/Admin/Details/PanelDetails.php?error=AlreadyExists'); // redirection vers la page actuelle avec erreurs
                    exit();
                }

            case "GENRES": 
                $genreId = intval($_POST["genre_id"]);

                $actionValidDeleteOnTableGenre = $crudGenre->supprimerGenre($genreId);

                if ($actionValidDeleteOnTableGenre) {
                    header('Location: /App/Views/Admin/Details/PanelDetails.php?table=GENRES'); // redirection vers la page actuelle actualisée
                    exit();
                }
                else {
                    header('Location: /App/Views/Admin/Details/PanelDetails.php?error=AlreadyExists'); // redirection vers la page actuelle avec erreurs
                    exit();
                }
            default:
                header('');
                exit();
        }
    }
}
else {
    exit(); // prevoir une page d'erreur
}



?>