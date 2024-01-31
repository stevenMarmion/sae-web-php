<?php

namespace App\Views\Admin\Details;

require_once __DIR__ . '/../../../Autoloader/autoloader.php';

// Tous ces require sont temporaire, comprendre pourquoi l'Autoloader ne fonctionne pas...
require_once __DIR__ . '/../../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once __DIR__ . '/../../../Models/EntityOperations/CrudAlbum.php';
require_once __DIR__ . '/../../../Models/EntityOperations/CrudUser.php';
require_once __DIR__ .'/../../../Models/EntityOperations/CrudFavoris.php';
require_once __DIR__ .'/../../../Models/EntityOperations/CrudComposer.php';
require_once __DIR__ .'/../../../Models/EntityOperations/CrudInterprete.php';
require_once __DIR__ .'/../../../Models/EntityOperations/CrudArtiste.php';
require_once __DIR__ .'/../../../Models/EntityOperations/CrudEtre.php';
require_once __DIR__ .'/../../../Models/EntityOperations/CrudGenre.php';
require_once __DIR__ .'/../../../Models/User.php';
require_once __DIR__ .'/../../../Models/Favori.php';
require_once __DIR__ .'/../../../Models/Album.php';
require_once __DIR__ .'/../../../Models/Artiste.php';
require_once __DIR__ .'/../../../Models/Genre.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudAlbum;
use \App\Models\EntityOperations\CrudUser;
use \App\Models\EntityOperations\CrudComposer;
use \App\Models\EntityOperations\CrudInterprete;
use \App\Models\EntityOperations\CrudFavoris;
use \App\Models\EntityOperations\CrudArtiste;
use \App\Models\EntityOperations\CrudGenre;
use \App\Models\EntityOperations\CrudEtre;
use \App\Models\User;
use \App\Models\Favori;
use \App\Models\Album;
use \App\Models\Artiste;
use \App\Models\Genre;

Autoloader::register();

$db = new ConnexionBDD();

if (isset($_GET['table'])) {
    if ($_GET['table'] == "UTILISATEUR") {
        $crudUser = new CrudUser($db::obtenir_connexion());
        $crudFavoris = new CrudFavoris($db::obtenir_connexion());
        $crudAlbum = new CrudAlbum($db::obtenir_connexion());

        $allUsers = $crudUser->obtenirTousUtilisateurs();
        $allUsersObject = [];

        foreach ($allUsers as $user) {
            $isAdmin = $user["isAdmin"] === 1 ? true : false;
            $currentUser = new User($user["idU"],$user["pseudo"],$user["mdp"],$user["adresseMail"],$isAdmin,[]);
            $allFavoris = $crudFavoris->obtenirFavorisParUtilisateur($currentUser->getId());

            foreach ($allFavoris as $favori) {
                $currentFavori = new Favori($favori["idU"], $favori["idAl"]);
                $currentAlbum = $crudAlbum->obtenirAlbumParId($currentFavori->getIdAlbum());
                $currentUser->ajouterFavori($currentAlbum["id"]);
            }
            $allUsersObject[] = $currentUser;
        }
    }
}

if (isset($_GET['table'])) {
    if ($_GET['table'] == "ALBUMS") {
        $crudInterpreter = new CrudInterprete($db::obtenir_connexion());
        $crudComposer = new CrudComposer($db::obtenir_connexion());
        $crudArtiste = new CrudArtiste($db::obtenir_connexion());
        $crudGenre = new CrudGenre($db::obtenir_connexion());
        $crudEtre = new CrudEtre($db::obtenir_connexion());
        $crudAlbum = new CrudAlbum($db::obtenir_connexion());

        $allAlbums = $crudAlbum->obtenirTousAlbums();
        $allAlbumsObject = [];

        foreach ($allAlbums as $album) {
            $currentAlbum = new Album($album["id"],$album["img"] ?? "",$album["dateDeSortie"],$album["titre"],[],[],[]);

            // Récupérer les compositeurs
            $compositeurs = $crudComposer->obtenirCompositeursParAlbum($album["id"]);
            foreach ($compositeurs as $compositeur) {
                $currentArtiste = $crudArtiste->obtenirArtisteParId($compositeur["idA"]);
                $currentCompositeur = new Artiste($compositeur["idA"],$currentArtiste['nomA']);
                $currentAlbum->ajouterCompositeur($currentCompositeur);
            }

            // Récupérer les interprètes
            $interpretes = $crudInterpreter->obtenirInterpretesParAlbum($album["id"]);
            foreach ($interpretes as $interprete) {
                $currentArtiste = $crudArtiste->obtenirArtisteParId($interprete["idA"]);
                $currentInterprete = new Artiste($interprete["idA"],$currentArtiste['nomA']);
                $currentAlbum->ajouterInterprete($currentInterprete);
            }

            // Récupérer les genres
            $genres = $crudEtre->obtenirGenresParMusique($album["id"]);
            foreach ($genres as $genre) {
                $currentGenre = $crudGenre->obtenirGenreParId($genre["idG"]);
                $currentGenre = new Genre($genre["idG"],$currentGenre['nomG']);
                $currentAlbum->ajouterGenre($currentGenre);
            }
            $allAlbumsObject[] = $currentAlbum;
        }
    }
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Admin/Details/panel-admin-details-style.css">
    <!-- <script src="/Public/JS/Admin/ActionsDelete.js" defer></script> -->
    <title>Panel - Admin</title>
</head>

<body>
    <?php 
        include __DIR__ . '/../../Layout/Admin/AdminNavBar.php';
    ?>

    <?php if (isset($_GET['table']) && ($_GET['table'] == "UTILISATEUR")) : ?>
    <section>
        <h1>Liste de tous les utilisateurs</h1>
        <table border='1'>
            <tr>
                <th>ID Utilisateur</th>
                <th>Pseudo</th>
                <th>Mot de passe</th>
                <th>Adresse Mail</th>
                <th>Admin</th>
                <th>Favoris</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($allUsersObject as $user): ?>
                <tr>
                    <td><?= $user->getId() ?></td>
                    <td><?= $user->getPseudo() ?></td>
                    <td><?= $user->getMdp() ?></td>
                    <td><?= $user->getMail() ?></td>
                    <td><?= $user->isAdmin() ? "Oui" : "Non" ?></td>
                    <td>
                        <?php foreach ($user->getFavoris() as $favori): ?>
                            ID Album: <?= $favori->getId() ?>, Titre: <?= $favori->getTitre() ?><br>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <div class="form-inline">
                            <form action="PanelUpdateUser.php?update=UTILISATEUR" method="post">
                                <input type="hidden" name="user_id" value="<?= $user->getId() ?>">
                                <input type="submit" class="custom-update-submit-button">
                            </form>
                            <form action="/App/Controllers/Admin/AdminDeleteController.php?delete=UTILISATEUR" method="post">
                                <input type="hidden" name="user_id" value="<?= $user->getId() ?>">
                                <input type="submit" class="custom-delete-submit-button">
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    </section>
    <?php endif; ?>

    <?php if (isset($_GET['table']) && ($_GET['table'] == "ALBUMS")) : ?>
    <section>
        <h1>Liste de tous les albums</h1>
        <table border='1'>
            <tr>
                <th>ID Album</th>
                <th>Date de sortie</th>
                <th>Titre</th>
                <th>Compositeurs</th>
                <th>Interprètes</th>
                <th>Genres</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($allAlbumsObject as $album): ?>
                <tr>
                    <td><?= $album->getId() ?></td>
                    <td><?= $album->getDateSortie() ?></td>
                    <td><?= $album->getTitre() ?></td>
                    <td>
                        <?php foreach ($album->getCompositeurs() as $compositeur): ?>
                            <?= $compositeur->getNomArtiste() ?><br>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php foreach ($album->getInterpretes() as $interprete): ?>
                            <?= $interprete->getNomArtiste() ?><br>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php foreach ($album->getGenres() as $genre): ?>
                            <?= $genre->getNomGenre() ?><br>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <div class="form-inline">
                            <form action="PanelUpdateAlbum.php?update=ALBUMS" method="post">
                                <input type="hidden" name="album_id" value="<?= $album->getId() ?>">
                                <input type="submit" class="custom-update-submit-button">
                            </form>
                            <form action="/App/Controllers/Admin/AdminDeleteController.php?delete=ALBUMS" method="post">
                                <input type="hidden" name="album_id" value="<?= $album->getId() ?>">
                                <input type="submit" class="custom-delete-submit-button">
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    </section>
    <?php endif; ?>

</body>

</html>
