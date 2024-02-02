<?php

require_once __DIR__ . '/../../Autoloader/autoloader.php';

require_once __DIR__ . '/../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once __DIR__ . '/../../Models/EntityOperations/CrudAlbum.php';
require_once __DIR__ . '/../../Models/EntityOperations/CrudUser.php';
require_once __DIR__ .'/../../Models/User.php';
require_once __DIR__ .'/../../Models/Album.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudAlbum;
use \App\Models\EntityOperations\CrudUser;
use \App\Models\User;
use \App\Models\Album;

Autoloader::register();

$db = new ConnexionBDD();
$crudUser = new CrudUser($db::obtenir_connexion());
$crudAlbum = new CrudAlbum($db::obtenir_connexion());

if (isset($_SERVER["REQUEST_METHOD"]) && isset($_GET["delete"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tableToUpdate = $_GET["delete"]; // UTILISATEUR ou ALBUMS
        if ($tableToUpdate === "UTILISATEUR") {
            $userId = $_POST["user_id"];
            $actionValid = $crudUser->supprimerUtilisateur($userId);
            if ($actionValid) {
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
            $actionValid = $crudAlbum->supprimerAlbum($albumId);
            if ($actionValid) {
                header('Location: /App/Views/Admin/Details/PanelDetails.php?table=ALBUMS'); // redirection vers la page actuelle actualisée
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