<?php

declare(strict_types=1);


require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudUser;
use App\Models\EntityOperations\CrudFavoris;
use App\Models\Favori;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\User;

Autoloader::register();

$db = new ConnexionBDD();
$crudUser = new CrudUser($db::obtenir_connexion());
$crudFavoris = new CrudFavoris($db::obtenir_connexion());

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: /App/Views/Auth/UserLogin.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

$userId = intval($_SESSION["id"]) ?? null;
if (!empty($userId)) {
    $currentUser = $crudUser->obtenirUtilisateurParId($userId);
    $allUsersObject = [];
    $isAdmin = $currentUser["isAdmin"] == "1" ? true : false;
    $currentUser = new User($currentUser["idU"],
                            $currentUser["pseudo"],
                            $currentUser["mdp"],
                            $currentUser["adresseMail"],
                            $isAdmin,
                            []);
    $allFavoris = $crudFavoris->obtenirFavorisParUtilisateur($currentUser->getId());
    $allUsersObject[] = $currentUser;
}

$errorDetected = null;

if (isset($_GET['error'])) {
    $errorDetected = true;
    $error = $_GET['error'];
    switch ($error) {
        case 'AlreadyExists':
            $alreadyExists = true;
            break;
        default:
            $alreadyExists = true;
            break;
    }
}
else {
    $errorDetected = false;
}

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

    <h2>Profil de <?= $currentUser->getPseudo() ?></h2>
    <section>
        <form action="/App/Controllers/Profil/UserUpdateController.php" method="post">
            <input type="hidden" name="user_id" value="<?= $currentUser->getId() ?>">
            <input type="hidden" name="isAdmin" value="<?= $currentUser->isAdmin() == true ? "true" : "false" ?>">

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
            <?php if ($errorDetected) : ?>
                <p class="erreur-update">
                    <?php if ($alreadyExists) : ?>
                        Ce pseudo existe déjà, veuillez en choisir un autre...
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </form>
    </section>
    <?php if ($currentUser->isAdmin() == true) : ?>
        <section>
            <h2 class="panel-button">Accéder au panel admin</h2>
            <a href='/App/Views/Admin/PanelAdmin.php' class="button-link">
                <button>Gérer la plateforme</button>
            </a>
        </section>
    <?php endif; ?>
</body>
</html>

