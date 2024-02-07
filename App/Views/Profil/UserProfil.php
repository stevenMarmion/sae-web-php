<?php

declare(strict_types=1);


require_once __DIR__ . '/../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudUser;
use Database\DatabaseConnection\ConnexionBDD;

Autoloader::register();

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: /login.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

$idCurrentUser = $_SESSION['id'];

$db = new ConnexionBDD();
$crudUser = new CrudUser($db::obtenir_connexion());

$userDatas = $crudUser->obtenirUtilisateurParId($idCurrentUser);
// $userDatas["idU"] = ....
// $userDatas["pseudo"] = ... 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Profile/profile-style.css">
    <title>Profil Utilisateur</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../Layout/NavMenu.php'; // Inclure le menu de navigation
    ?>

    <h2>Profil Utilisateur</h2>

    <div class="profile-info">
        <p><strong>Pseudo :</strong> <?php echo $userDatas['pseudo']; ?></p>
        <p><strong>Email :</strong> <?php echo $user['adresseMail']; ?></p>
        <p><strong>Mot de passe :</strong> ********</p>

    </div>

    <a href="/logout.php">Se déconnecter</a>

</body>
</html>
