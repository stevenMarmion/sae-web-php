<?php

namespace App\Controllers;

require_once __DIR__ . '/../../Autoloader/autoloader.php';
require_once __DIR__ .'/../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once __DIR__ .'/../../Models/EntityOperations/CrudUser.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudUser;

Autoloader::register();

$instance = new ConnexionBDD();

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['pseudo']) && isset($_POST['mdp'])) {
        authentifaction($instance);
    } else {
        header('Location: ' . __DIR__ . '/../Views/Auth/UserLogin.php?error=1');
        exit();
    }
}

function authentifaction($instance) {
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];

    $crudUser = new CrudUser($instance::obtenir_connexion());
    $estAutentifie = $crudUser->isAuth($pseudo, $mdp);

    if ($estAutentifie) {
        $datas = $crudUser->obtenirUtilisateurParPseudo($pseudo);
        $_SESSION["id"] = $datas["idU"];
        $_SESSION["pseudo"] = $datas["pseudo"];
        header('Location: /App/Views/Home/Accueil.php');
        exit();
    } else {
        echo "Identifiants incorrects. Veuillez réessayer.";
        header('Location: /App/Views/Auth/UserLogin.php?error=No-account');
        exit();
    }
}

?>
