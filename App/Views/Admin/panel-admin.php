<?php

namespace App\Views\Admin;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

// Tous ces require sont temporaire, comprendre pourquoi l'Autoloader ne fonctionne pas...
require_once __DIR__ .'/../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once __DIR__ .'/../../Models/EntityOperations/CrudAlbum.php';
require_once __DIR__ .'/../../Models/EntityOperations/CrudUser.php';
require_once __DIR__ .'/../../Models/EntityOperations/CrudFavoris.php';
require_once __DIR__ .'/../../Models/User.php';
require_once __DIR__ .'/../../Models/Favori.php';

use App\Autoloader\Autoloader;
USE Database\DatabaseConnection\ConnexionBDD;
use App\Models\EntityOperations\CrudAlbum;
use App\Models\EntityOperations\CrudUser;
use App\Models\EntityOperations\CrudFavoris;
use App\Models\User;
use App\Models\Favori;

Autoloader::register();

$db = ConnexionBDD::obtenir_connexion();
$crudUser = new CrudUser($db);
$crudFavoris = new CrudFavoris($db);
$crudAlbum = new CrudAlbum($db);

$allUsers = $crudUser->obtenirTousUtilisateurs();
$allUsersObject = [];

foreach ($allUsers as $user) {
    $isAdmin = $user["isAdmin"] === 1 ? true : false;
    $currentUser = new User($user["idU"],
                    $user["pseudo"],
                    $user["mdp"],
                    $user["adresseMail"],
                    $isAdmin,
                    []);

    $allFavoris = $crudFavoris->obtenirFavorisParUtilisateur($currentUser->getId());

    foreach ($allFavoris as $favori) {
        $currentFavori = new Favori($favori["idU"], $favori["idAl"]);
        $currentAlbum = $crudAlbum->obtenirAlbumParId($currentFavori->getIdAlbum());
        $currentUser->ajouterFavori($currentAlbum);
    }
    $allUsersObject[] = $currentUser;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - Admin</title>
</head>

<body>
    <h1>Liste de tous les utilisateur</h1>
    <table border='1'>
        <tr>
            <th>ID Utilisateur</th>
            <th>Pseudo</th>
            <th>Mot de passe</th>
            <th>Adresse Mail</th>
            <th>Admin</th>
            <th>Favoris</th>
        </tr>

        <?php foreach ($allUsersObject as $user): ?>
            <tr>
                <td><?= $user->getId() ?></td>
                <td><?= $user->getPseudo() ?></td>
                <td><?= $user->getMdp() ?></td>
                <td><?= $user->getMail() ?></td>
                <td><?= $user->isAdmin() ? "Oui" : "Non" ?></td>
                <td>
                    <?php foreach ($user->getFavoris() as $favori): ?>
                        ID Album: <?= $favori->getId() ?>, Titre: <?= $favori->getTitre() ?><br>
                    <?php endforeach; ?>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

</body>

</html>