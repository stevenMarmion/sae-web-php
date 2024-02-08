<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudUser;
use  \App\Models\User;

Autoloader::register();

$db = new ConnexionBDD();
$crudUser = new CrudUser($db::obtenir_connexion());

if (isset($_SERVER["REQUEST_METHOD"])&& $_SERVER["REQUEST_METHOD"] == "POST") {
    // Prépare les données pour le CRUD USER
    $userId = intval($_POST["user_id"]) ?? null;
    $userName = $_POST["pseudo"] ?? null;
    $userMail = $_POST["adresseMail"] ?? null;
    $userMdp = $_POST["mdp"] ?? null;
    $userIsAdmin = $_POST["isAdmin"] == "true" ? true : false;
    $user = new User($userId, $userName, $userMdp, $userMail, $userIsAdmin, []);
    $actionValid = $crudUser->modifierUtilisateur($userId, $user);
    if ($actionValid) {
        header('Location: /App/Views/Profil/UserProfil.php'); // redirection vers la page actuelle actualisée
        exit();
    }
    else {
        header('Location: /App/Views/Profil/UserProfil.php?error=cacaerror'); // redirection vers la page actuelle actualisée
        exit();
    }
    }

?>