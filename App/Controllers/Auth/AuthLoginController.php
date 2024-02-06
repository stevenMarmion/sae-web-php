<?php

declare(strict_types=1);

namespace App\Controllers;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudUser;

Autoloader::register();

$instance = new ConnexionBDD();

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['pseudo']) && isset($_POST['mdp'])) {
        authentifaction($instance);
    }
}

function authentifaction($instance) {
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];

    $crudUser = new CrudUser($instance::obtenir_connexion());
    $datas = $crudUser->obtenirUtilisateurParPseudo($pseudo);

    if ($datas == false) { // cela signifie que nous trouvons personne avec le pseudo actuel
        header('Location: /App/Views/Auth/UserLogin.php?error=No-account');
        exit();
    }
    else {
        if ($crudUser->isAuth($pseudo, $mdp)) { // on vérifie que le pseudo colle au mot de passe
            $_SESSION["id"] = $datas["idU"];
            $_SESSION["pseudo"] = $datas["pseudo"];
            header('Location: /App/Views/Home/Accueil.php');
            exit();
        } else { // la personne s'est trompée de mot de passe
            echo "Identifiants incorrects. Veuillez réessayer.";
            header('Location: /App/Views/Auth/UserLogin.php?error=BadPassword');
            exit();
        }   
    }
}

?>
