<?php

require_once __DIR__ . '/../../Autoloader/autoloader.php';

require_once __DIR__ . '/../../../Database/DatabaseConnection/ConnexionBDD.php';
// require_once __DIR__ . '/../../Models/EntityOperations/CrudAlbum.php';
// require_once __DIR__ . '/../../Models/EntityOperations/CrudUser.php';
// require_once __DIR__ . '/../../Models/EntityOperations/CrudPlaylist.php';
// require_once __DIR__ . '/../../Models/EntityOperations/CrudNote.php';
// require_once __DIR__ .'/../../Models/User.php';
// require_once __DIR__ .'/../../Models/Album.php';

use \App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudGenre;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudAlbum;
use \App\Models\EntityOperations\CrudUser;
use \App\Models\EntityOperations\CrudArtiste;
use \App\Models\EntityOperations\CrudPlaylist;
use \App\Models\EntityOperations\CrudNote;
use \App\Models\User;
use \App\Models\Album;

Autoloader::register();

$db = new ConnexionBDD();
$crudUser = new CrudUser($db::obtenir_connexion());
$crudAlbum = new CrudAlbum($db::obtenir_connexion());
$crudArtiste = new CrudArtiste($db::obtenir_connexion());
$crudGenre = new CrudGenre($db::obtenir_connexion());

if (isset($_SERVER["REQUEST_METHOD"]) && isset($_GET["delete"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tableToUpdate = $_GET["delete"]; // UTILISATEUR ou ALBUMS
        if ($tableToUpdate === "UTILISATEUR") {
            $userId = $_POST["user_id"];

            $actionValidDeleteOnTableUser = $crudUser->supprimerUtilisateur($userId);

            if ($actionValidDeleteOnTableUser) {
                header('Location: /App/Views/Admin/Details/PanelDetails.php?table=UTILISATEUR'); // redirection vers la page actuelle actualisée
                exit();
            }
            else {
                header('Location: /App/Views/Admin/Details/PanelDetails.php?error=NullValues'); // redirection vers la page actuelle avec erreurs
                exit();
            }
        }
        if ($tableToUpdate === "ALBUMS") {
            $albumId = $_POST["album_id"];

            $actionValidDeleteOnTableAlbum = $crudAlbum->supprimerAlbum($albumId);

            if ($actionValidDeleteOnTableAlbum) {
                header('Location: /App/Views/Admin/Details/PanelDetails.php?table=ALBUMS'); // redirection vers la page actuelle actualisée
                exit();
            }
            else {
                header('Location: /App/Views/Admin/Details/PanelDetails.php?error=NullValues'); // redirection vers la page actuelle avec erreurs
                exit();
            }
        }

        if ($tableToUpdate === "ARTISTES") {
            $artisteId = $_POST["artiste_id"];

            $actionValidDeleteOnTableArtiste = $crudArtiste->supprimerArtiste($artisteId);

            if ($actionValidDeleteOnTableArtiste) {
                header('Location: /App/Views/Admin/Details/PanelDetails.php?table=ARTISTES'); // redirection vers la page actuelle actualisée
                exit();
            }
            else {
                header('Location: /App/Views/Admin/Details/PanelDetails.php?error=NullValues'); // redirection vers la page actuelle avec erreurs
                exit();
            }
        }
        if ($tableToUpdate === "GENRES") {
            $genreId = $_POST["genre_id"];

            $actionValidDeleteOnTableGenre = $crudGenre->supprimerGenre($genreId);

            if ($actionValidDeleteOnTableGenre) {
                header('Location: /App/Views/Admin/Details/PanelDetails.php?table=GENRES'); // redirection vers la page actuelle actualisée
                exit();
            }
            else {
                header('Location: /App/Views/Admin/Details/PanelDetails.php?error=NullValues'); // redirection vers la page actuelle avec erreurs
                exit();
            }
        }
    }
    else {
        exit(); // prevoir une page d'erreur
    }
}
else {
    exit(); // prevoir une page d'erreur
}



?>