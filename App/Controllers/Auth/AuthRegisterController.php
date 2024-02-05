<?php

require_once __DIR__ . '/../../Autoloader/autoloader.php';
require_once __DIR__ .'/../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once __DIR__ .'/../../Models/EntityOperations/CrudUser.php';
require_once __DIR__ .'/../../Models/User.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudUser;
use \App\Models\User;

Autoloader::register();

$instance = new ConnexionBDD();

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
                echo "Mot de passe différent";
                //header('Location: ' . __DIR__ . '/../Views/Auth/UserRegister.php?error=2');  // Mot de passe et confirmation ne correspondent pas
                exit();
            }

            // Enregistrer l'utilisateur en BDD
            $crudUser = new CrudUser($instance::obtenir_connexion());
            $user = new User(0, $pseudo, $mdp, $email, false, []); // nous mettons 0 car l'insertion est en AUTO INCREMENT dans tout les cas
            $inscriptionReussie = $crudUser->ajouterUtilisateurFromObject($user);
            echo $inscriptionReussie;

            if ($inscriptionReussie) {
                if (!isset($_SESSION)){
                    session_start();
                }
                else {
                    session_destroy();
                    session_start();
                }
                $_SESSION['idU'] = $crudUser->obtenirUtilisateurParPseudo($pseudo)["idU"];
                header('Location: /App/Views/Home/accueil.php');
                exit();
            } else {
                echo "Identifiants incorrects. Veuillez réessayer.";
                //header('Location: ' . __DIR__ . '/../Views/Auth/UserRegister.php?error=3'); // Erreur lors de l'inscription
                exit();
            }
    } else {
        echo "Toutes les valeurs ne sont pas présentes. Veuillez réessayer.";
        //header('Location: ' . __DIR__ . '/../Views/Auth/UserRegister.php?error=1'); // Toutes les valeurs _POST ne sont pas présentes
        exit();
    }
}
