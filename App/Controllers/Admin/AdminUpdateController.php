<?php

namespace App\Controllers\Admin;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

require_once __DIR__ . '/../../../Database/DatabaseConnection/ConnexionBDD.php';
// require_once __DIR__ . '/../../Models/EntityOperations/CrudAlbum.php';
// require_once __DIR__ . '/../../Models/EntityOperations/CrudUser.php';
// require_once __DIR__ .'/../../Models/EntityOperations/CrudFavoris.php';
// require_once __DIR__ .'/../../Models/User.php';
// require_once __DIR__ .'/../../Models/Album.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudAlbum;
use \App\Models\EntityOperations\CrudUser;
use \App\Models\EntityOperations\CrudFavoris;
use \App\Models\EntityOperations\CrudArtiste;
use \App\Models\EntityOperations\CrudGenre;
use \App\Models\User;
use \App\Models\Album;
use \App\Models\Artiste;
use \App\Models\Genre;

Autoloader::register();

$db = new ConnexionBDD();
$crudUser = new CrudUser($db::obtenir_connexion());
$crudFavoris = new CrudFavoris($db::obtenir_connexion());
$crudAlbum = new CrudAlbum($db::obtenir_connexion());
$crudArtiste = new CrudArtiste($db::obtenir_connexion());
$crudGenre = new CrudGenre($db::obtenir_connexion());

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
                $albumCompositeurs[] = $_POST["tous-compositeurs-$i"] ?? null;
                $ancienComp[] = $_POST["ancien-compositeurs-$i"] ?? null;
            }
            for ($i = 0; $i < $nbInt; $i++) {
                $albumInterpretes[] = $_POST["tous-interpretes-$i"] ?? null;
                $ancienInt[] = $_POST["ancien-interpretes-$i"] ?? null;
            }
            for ($i = 0; $i < $nbGenres; $i++) {
                $albumGenres[] = $_POST["tous-genres-$i"] ?? null;
                $ancienGenres[] = $_POST["ancien-genres-$i"] ?? null;
            }
            $album = new Album($albumId, $albumImg, $albumDateSortie, $albumName, $albumCompositeurs, $albumInterpretes, $albumGenres); // $albumCompositeur = 5
            $actionValid = $crudAlbum->modifierAlbum($albumId, $album, $ancienComp, $ancienInt, $ancienGenres); // ancien comp = 3
            if ($actionValid) {
                header('Location: /App/Views/Admin/Details/PanelDetails.php?table=ALBUMS'); // redirection vers la page actuelle actualisée
                exit();
            }
            else {
                print_r($albumCompositeurs);
                print_r($ancienComp);
                print_r($albumInterpretes);
                print_r($ancienInt);
                //header('Location: /App/Views/Admin/Details/PanelDetails.php?error=NullValues'); // redirection vers la page actuelle avec erreurs
                //exit();
            }
        }
        if ($tableToUpdate === "ARTISTES") {
            $artisteId = $_POST["artiste_id"] ?? null;
            $nomArtiste = $_POST["nom"] ?? null;

            $artiste = new Artiste($artisteId, $nomArtiste);

            $actionValid = $crudArtiste->modifierArtiste($artisteId, $artiste);
            if ($actionValid) {
                header('Location: /App/Views/Admin/Details/PanelDetails.php?table=ARTISTES'); // redirection vers la page actuelle actualisée
                exit();
            }
            else {
                header('Location: /App/Views/Admin/Details/PanelDetails.php?error=AlreadyExists'); // redirection vers la page actuelle avec erreurs
                exit();
            }
        }
        if ($tableToUpdate === "GENRES") {
            $genreId = $_POST["genre_id"] ?? null;
            $nomGenre = $_POST["nom"] ?? null;

            $genre = new Genre($genreId, $nomGenre);

            $actionValid = $crudGenre->modifierGenre($genreId, $genre);
            if ($actionValid) {
                header('Location: /App/Views/Admin/Details/PanelDetails.php?table=GENRES'); // redirection vers la page actuelle actualisée
                exit();
            }
            else {
                header('Location: /App/Views/Admin/Details/PanelDetails.php?error=AlreadyExists'); // redirection vers la page actuelle avec erreurs
                exit();
            }
        }
    }
}

?>
