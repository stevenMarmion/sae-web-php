<?php

require_once __DIR__ . '/../../../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudUser;
use \App\Models\User;

Autoloader::register();

$db = new ConnexionBDD();
$crudUser = new CrudUser($db::obtenir_connexion());

if (isset($_SERVER["REQUEST_METHOD"]) && isset($_GET["delete"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tableToUpdate = $_GET["delete"]; // UTILISATEUR
        if ($tableToUpdate === "UTILISATEUR") {
            $userId = $_POST["user_id"];
            $listeUsers = $crudUser->obtenirUtilisateurParId($userId);
            if ($listeUsers != false) {
                $user = new User($listeUsers["idU"], $listeUsers["pseudo"], $listeUsers["mdp"], $listeUsers["adresseMail"], $listeUsers["isAdmin"], []);
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
    <title>Delete - Admin - User</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../../../Layout/Admin/AdminNavBar.php';
    ?>
    <h1>Suppression d'utilisateur</h1>

    <form action="/App/Controllers/Admin/AdminDeleteController.php?delete=UTILISATEUR" method="post">
        <input type="hidden" id="user_id" name="user_id" value="<?= $user->getId() ?>">
        
        <p class="delete-warning">
            Etes-vous sur de vouloir supprimer l'utilisateur "<?= $user->getPseudo() ?>" ?
        </p>
        <p class="informations-warning">
            Si vous supprimez cet utilisateur, toutes ces playlists, favoris et toutes données relatives à son compte seront supprimés !
        </p>

        <div class="choice-delete">
            <button type="submit">Supprimer</button>
            <button type="button">
                <a href="/App/Views/Admin/Details/PanelDetails.php?table=UTILISATEUR">
                    Annuler
                </a>
            </button>
        </div>
    </form>

</body>
</html>
