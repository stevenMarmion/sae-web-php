<?php

declare(strict_types=1);

namespace App\Controllers\Details;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudNote;
use Database\DatabaseConnection\ConnexionBDD;

Autoloader::register();

session_start();

if (isset($_SESSION["id"]) && isset($_SERVER["REQUEST_METHOD"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["rating"]) && isset($_POST["album_id"])) {
            $db = new ConnexionBDD();
            $crudNote = new CrudNote($db::obtenir_connexion());

            $note = intval($_POST["rating"]);
            $idAlbum = intval($_POST["album_id"]);
            $idUser = intval($_SESSION["id"]);

            $estNote = $crudNote->obtenirNoteUtilisateurPourAlbum($idAlbum, $idUser);
            if (is_array($estNote)) {
                if ($note == intval($estNote["note"])) { // signifie qu'on donne la même note à l'album que ce qu'elle à déjà, on donne la note -1 alors
                    $crudNote->modifierNote($idAlbum, $idUser, $note-1);
                }
                else {
                    $crudNote->modifierNote($idAlbum, $idUser, $note);
                }
            }
            else {
                $crudNote->ajouterNote($idAlbum, $idUser, $note);
            }
            header('Location: /App/Views/Details/DetailAlbum.php?id=' . $idAlbum);
            exit();
        }
    }
}


?>