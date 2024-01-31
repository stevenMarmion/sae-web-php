<?php

namespace App\Controllers\Admin;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

require_once __DIR__ . '/../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once __DIR__ . '/../../Models/EntityOperations/CrudAlbum.php';
require_once __DIR__ . '/../../Models/EntityOperations/CrudUser.php';
require_once __DIR__ .'/../../Models/EntityOperations/CrudFavoris.php';
require_once __DIR__ .'/../../Models/EntityOperations/CrudComposer.php';
require_once __DIR__ .'/../../Models/EntityOperations/CrudInterprete.php';
require_once __DIR__ .'/../../Models/EntityOperations/CrudArtiste.php';
require_once __DIR__ .'/../../Models/EntityOperations/CrudEtre.php';
require_once __DIR__ .'/../../Models/EntityOperations/CrudGenre.php';
require_once __DIR__ .'/../../Models/User.php';
require_once __DIR__ .'/../../Models/Favori.php';
require_once __DIR__ .'/../../Models/Album.php';
require_once __DIR__ .'/../../Models/Artiste.php';
require_once __DIR__ .'/../../Models/Genre.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudAlbum;
use \App\Models\EntityOperations\CrudUser;
use \App\Models\EntityOperations\CrudComposer;
use \App\Models\EntityOperations\CrudInterprete;
use \App\Models\EntityOperations\CrudFavoris;
use \App\Models\EntityOperations\CrudArtiste;
use \App\Models\EntityOperations\CrudGenre;
use \App\Models\EntityOperations\CrudEtre;
use \App\Models\User;
use \App\Models\Favori;
use \App\Models\Album;
use \App\Models\Artiste;
use \App\Models\Genre;

Autoloader::register();

$db = new ConnexionBDD();
$crudUser = new CrudUser($db::obtenir_connexion());
$crudFavoris = new CrudFavoris($db::obtenir_connexion());
$crudAlbum = new CrudAlbum($db::obtenir_connexion());

if (isset($_SERVER["REQUEST_METHOD"]) && isset($_GET["update"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tableToUpdate = $_GET["update"]; // UTILISATEUR ou ALBUMS
        if ($tableToUpdate === "UTILISATEUR") {
            $userId = $_POST["user_id"] ?? null;
        }
        if ($tableToUpdate === "ALBUMS") {
            $albumId = $_POST["album_id"] ?? null;
        }
    }
}

?>
