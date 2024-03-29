<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudUser;
use \App\Models\User;

Autoloader::register();

$instance = new ConnexionBDD();

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['pseudo']) && isset($_POST['email']) &&
        isset($_POST['mdp']) && isset($_POST['confirmer_mdp']) &&
        !empty($_POST['pseudo']) && !empty($_POST['email']) &&
        !empty($_POST['mdp']) && !empty($_POST['confirmer_mdp'])) {

            $pseudo = $_POST['pseudo'];
            $email = $_POST['email'];
            $mdp = $_POST['mdp'];
            $confirmer_mdp = $_POST['confirmer_mdp'];

            // Vérifier si les mots de passe correspondent
            if ($mdp !== $confirmer_mdp) {
                header('Location: /App/Views/Auth/UserRegister.php?error=BadPassword'); // Erreur lors de l'inscription
                exit();
            }

            // Enregistrer l'utilisateur en BDD
            $crudUser = new CrudUser($instance::obtenir_connexion());
            $user = new User(0, $pseudo, $mdp, $email, false, []); // nous mettons 0 car l'insertion est en AUTO INCREMENT dans tout les cas
            $inscriptionReussie = $crudUser->ajouterUtilisateurFromObject($user);

            if ($inscriptionReussie) {
                $datas = $crudUser->obtenirUtilisateurParPseudo($user->getPseudo());
                $_SESSION["id"] = $datas["idU"];
                $_SESSION["pseudo"] = $datas["pseudo"];
                header('Location: /App/Views/Home/Accueil.php');
                exit();
            } else {
                header('Location: /App/Views/Auth/UserRegister.php?error=AlreadyExists'); // Erreur lors de l'inscription
                exit();
            }
    } else {
        echo "Toutes les valeurs ne sont pas présentes. Veuillez réessayer.";
        //header('Location: ' . __DIR__ . '/../Views/Auth/UserRegister.php?error=1'); // Toutes les valeurs _POST ne sont pas présentes
        exit();
    }
}