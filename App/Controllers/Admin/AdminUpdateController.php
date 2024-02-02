<?php

namespace App\Controllers\Admin;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

require_once __DIR__ . '/../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once __DIR__ . '/../../Models/EntityOperations/CrudAlbum.php';
require_once __DIR__ . '/../../Models/EntityOperations/CrudUser.php';
require_once __DIR__ .'/../../Models/EntityOperations/CrudFavoris.php';
require_once __DIR__ .'/../../Models/User.php';
require_once __DIR__ .'/../../Models/Album.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudAlbum;
use \App\Models\EntityOperations\CrudUser;
use \App\Models\EntityOperations\CrudFavoris;
use \App\Models\User;
use \App\Models\Album;

Autoloader::register();

$db = new ConnexionBDD();
$crudUser = new CrudUser($db::obtenir_connexion());
$crudFavoris = new CrudFavoris($db::obtenir_connexion());
$crudAlbum = new CrudAlbum($db::obtenir_connexion());

if (isset($_SERVER["REQUEST_METHOD"]) && isset($_GET["update"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tableToUpdate = $_GET["update"]; // UTILISATEUR ou ALBUMS
        if ($tableToUpdate === "UTILISATEUR") {
            // Prépare les données pour le CRUD USER
            $userId = $_POST["user_id"] ?? null;
            $userName = $_POST["pseudo"] ?? null;
            $userMail = $_POST["adresseMail"] ?? null;
            $userMdp = $_POST["mdp"] ?? null;
            $userIsAdmin = $_POST["isAdmin"] == "true" ? true : false;
            $user = new User($userId, $userName, $userMdp, $userMail, $userIsAdmin, []);
            $actionValid = $crudUser->modifierUtilisateur($userId, $user);
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
            // Récupère les anciennes données pour la modif CRUD ALBUM
            $ancienComp = [];
            $ancienInt = [];
            $ancienGenres = [];

            $albumCompositeurs = [];
            $albumInterpretes = [];
            $albumGenres = [];

            $nbComp = $_POST["nb-compositeurs"] ?? null;
            $nbInt = $_POST["nb-interpretes"] ?? null;
            $nbGenres = $_POST["nb-genres"] ?? null;

            // Prépare les nouvelles données pour le CRUD ALBUM
            $albumId = $_POST["album_id"] ?? null;
            $albumImg = $_POST["album_img"] ?? null;
            $albumName = $_POST["titre"] ?? null;
            $albumDateSortie = $_POST["dateDeSortie"] ?? null;

            for ($i = 0; $i < $nbComp; $i++) {
                $albumCompositeurs[] = $_POST["compositeurs-$i"] ?? null;
                $ancienComp[] = $_POST["ancien-compositeurs-$i"] ?? null;
            }
            for ($i = 0; $i < $nbInt; $i++) {
                $albumInterpretes[] = $_POST["interpretes-$i"] ?? null;
                $ancienInt[] = $_POST["ancien-interpretes-$i"] ?? null;
            }
            for ($i = 0; $i < $nbGenres; $i++) {
                $albumGenres[] = $_POST["tous-genres-$i"] ?? null;
                $ancienGenres[] = $_POST["ancien-genres-$i"] ?? null;
            }
            $album = new Album($albumId, $albumImg, $albumDateSortie, $albumName, $albumCompositeurs, $albumInterpretes, $albumGenres);
            $actionValid = $crudAlbum->modifierAlbum($albumId, $album, $ancienComp, $ancienInt, $ancienGenres);
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
}

?>
