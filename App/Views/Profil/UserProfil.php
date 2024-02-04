<?php

namespace App\Controllers\User;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

require_once __DIR__ . '/../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once __DIR__ . '/../../Models/EntityOperations/CrudAlbum.php';
require_once __DIR__ . '/../../Models/EntityOperations/CrudUser.php';
require_once __DIR__ . '/../../Models/EntityOperations/CrudFavoris.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Models/Favori.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudAlbum;
use \App\Models\EntityOperations\CrudUser;
use \App\Models\EntityOperations\CrudFavoris;
use \App\Models\Favori;

Autoloader::register();

$db = new ConnexionBDD();
$crudUser = new CrudUser($db::obtenir_connexion());
$crudFavoris = new CrudFavoris($db::obtenir_connexion());
$crudAlbum = new CrudAlbum($db::obtenir_connexion());


$userAuthenticated = true;

if ($userAuthenticated) {
    $userId = $_SESSION['user_id']; // Assuming you store user ID in session after login.
    $currentUser = $crudUser->obtenirUtilisateurParId($userId);
    $allFavoris = $crudFavoris->obtenirFavorisParUtilisateur($currentUser['id']);

    foreach ($allFavoris as $favori) {
        $currentFavori = new Favori($favori['idU'], $favori['idAl']);
        $currentAlbum = $crudAlbum->obtenirAlbumParId($currentFavori->getIdAlbum());
        #$currentUser->ajouterFavori($currentAlbum['id']);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/User/Profile/user-profile-style.css">
    <title>Profil</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../../Layout/User/UserNavBar.php'; // Change the path as needed.
    ?>
    <section>
        <h1>Profil de l'utilisateur</h1>

        <div class="user-details">
            <p><strong>Pseudo:</strong> <?= $currentUser['pseudo'] ?></p>
            <p><strong>Adresse Mail:</strong> <?= $currentUser['adresseMail'] ?></p>

            <h2>Favoris</h2>
            <?php foreach ($currentUser->getFavoris() as $favoriId): ?>
                <p>Favori ID: <?= $favoriId ?></p>
            <?php endforeach; ?>
        </div>
        <button onclick="window.location.href='/edit-profile.php'">Modifier</button>
    </section>
</body>
</html>
