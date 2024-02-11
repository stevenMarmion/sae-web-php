<?php

declare(strict_types=1);


require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudUser;
use App\Models\EntityOperations\CrudFavoris;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\User;

Autoloader::register();

$db = new ConnexionBDD();
$crudUser = new CrudUser($db::obtenir_connexion());
$crudFavoris = new CrudFavoris($db::obtenir_connexion());

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: /login.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}
$userId = intval($_SESSION["id"]) ?? null;
if (!empty($userId)) {
    $currentUser = $crudUser->obtenirUtilisateurParId($userId);
    $allUsersObject = [];
    $isAdmin = $currentUser["isAdmin"] == "1" ? true : false;
    $currentUser = new User($currentUser["idU"],$currentUser["pseudo"],$currentUser["mdp"],$currentUser["adresseMail"],$isAdmin,[]);
    $allFavoris = $crudFavoris->obtenirFavorisParUtilisateur($currentUser->getId());

    foreach ($allFavoris as $favori) {
        $currentFavori = new Favori($favori["idU"], $favori["idAl"]);
        $currentAlbum = $crudAlbum->obtenirAlbumParId($currentFavori->getIdAlbum());
        $currentUser->ajouterFavori($currentAlbum["id"]);
    }
    $allUsersObject[] = $currentUser;
}

$idCurrentUser = $_SESSION['id'];

$db = new ConnexionBDD();
$crudUser = new CrudUser($db::obtenir_connexion());

$userDatas = $crudUser->obtenirUtilisateurParId($idCurrentUser);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Profil/profile-style.css">
    <title>Profil Utilisateur</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../Layout/Auth/NavBar.php'; // Inclure le menu de navigation
    ?>
    <?php
        include __DIR__ . '/../Layout/Home/NavGenerique.php';
    ?>

    <h2>Profil de <?= $userDatas['pseudo'] ?></h2>
    <section>
        <form action="/App/Controllers/Profil/UserUpdateController.php" method="post">
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
                <label for="mdp">Mot de passe :</label>
                <input type="password" id="mdp" name="mdp" value="<?= $currentUser->getMdp() ?>" required>
            </div>
            <input type="submit" value="Mettre à jour">
            <a href="/App/Views/Auth/UserLogin.php">Se déconnecter</a>
        </form>
    </section>
</body>
</html>

