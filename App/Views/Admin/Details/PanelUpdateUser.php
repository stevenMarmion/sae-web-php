<?php

namespace App\Controllers\Admin;

require_once __DIR__ . '/../../../Autoloader/autoloader.php';

require_once __DIR__ . '/../../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once __DIR__ . '/../../../Models/EntityOperations/CrudAlbum.php';
require_once __DIR__ . '/../../../Models/EntityOperations/CrudUser.php';
require_once __DIR__ . '/../../../Models/EntityOperations/CrudFavoris.php';
require_once __DIR__ . '/../../../Models/User.php';
require_once __DIR__ . '/../../../Models/Favori.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudAlbum;
use \App\Models\EntityOperations\CrudUser;
use \App\Models\EntityOperations\CrudFavoris;
use \App\Models\User;
use \App\Models\Favori;

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
            if (!empty($userId)) {
                $currentUser = $crudUser->obtenirUtilisateurParId($userId);
                $allUsersObject = [];
                $isAdmin = $currentUser["isAdmin"] === 1 ? true : false;
                $currentUser = new User($currentUser["idU"],$currentUser["pseudo"],$currentUser["mdp"],$currentUser["adresseMail"],$isAdmin,[]);
                $allFavoris = $crudFavoris->obtenirFavorisParUtilisateur($currentUser->getId());

                foreach ($allFavoris as $favori) {
                    $currentFavori = new Favori($favori["idU"], $favori["idAl"]);
                    $currentAlbum = $crudAlbum->obtenirAlbumParId($currentFavori->getIdAlbum());
                    $currentUser->ajouterFavori($currentAlbum["id"]);
                }
                $allUsersObject[] = $currentUser;
            }
            if (!empty($allUsersObject)) {
                $currentUser = $allUsersObject[0];
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
    <link rel="stylesheet" href="/Public/Css/Admin/Details/panel-admin-update-user-style.css">
    <title>Modif - Admin - User</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../../Layout/Admin/AdminNavBar.php';
    ?>
    <section>
        <h1>Modifier les détails de l'utilisateur</h1>

        <form action="/App/Controllers/Admin/AdminUpdateController.php?update=UTILISATEUR" method="post">
            <input type="hidden" name="user_id" value="<?= $currentUser->getId() ?>">
            <input type="hidden" name="mdp" value="<?= $currentUser->getMdp() ?>">

            <div class="form-group">
                <label for="pseudo">Pseudo :</label>
                <input type="text" id="pseudo" name="pseudo" value="<?= $currentUser->getPseudo() ?>" required>
            </div>

            <div class="form-group">
                <label for="adresseMail">Adresse Mail :</label>
                <input type="email" id="adresseMail" name="adresseMail" value="<?= $currentUser->getMail() ?>" required>
            </div>

            <div class="form-group">
                <label for="isAdmin">Admin :</label>
                <div>
                    <input type="radio" id="isAdmin" name="isAdmin" value="true" <?= $currentUser->isAdmin() ? 'checked' : '' ?>>
                    <label for="adminTrue">True</label>

                    <input type="radio" id="isAdmin" name="isAdmin" value="false" <?= !$currentUser->isAdmin() ? 'checked' : '' ?>>
                    <label for="adminFalse">False</label>
                </div>
            </div>

            <input type="submit" value="Mettre à jour">
        </form>
    </section>
</body>
</html>