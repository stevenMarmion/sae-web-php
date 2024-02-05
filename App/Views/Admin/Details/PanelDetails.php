<?php

namespace App\Views\Admin\Details;

require_once __DIR__ . '/../../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudNote;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudAlbum;
use \App\Models\EntityOperations\CrudUser;
use \App\Models\EntityOperations\CrudComposer;
use \App\Models\EntityOperations\CrudInterprete;
use \App\Models\EntityOperations\CrudFavoris;
use \App\Models\EntityOperations\CrudArtiste;
use \App\Models\EntityOperations\CrudGenre;
use \App\Models\EntityOperations\CrudEtre;
use \App\Models\EntityOperations\CrudPlaylist;
use \App\Models\User;
use \App\Models\Favori;
use \App\Models\Album;
use \App\Models\Artiste;
use \App\Models\Genre;

Autoloader::register();

$db = new ConnexionBDD();

if (isset($_GET['table'])) {
    if ($_GET['table'] == "UTILISATEUR") {
        $crudFavoris = new CrudFavoris($db::obtenir_connexion());
        $crudUser = new CrudUser($db::obtenir_connexion());
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
    else if ($_GET['table'] == "ALBUMS") {
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
            if (sizeof($compositeurs) == 0) {
                $currentCompositeur = new Artiste(-1, "Inconnu");
                $currentAlbum->ajouterCompositeur($currentCompositeur);
            }
            else {
                foreach ($compositeurs as $compositeur) {
                    $currentArtiste = $crudArtiste->obtenirArtisteParId($compositeur["idA"]);
                    $currentCompositeur = new Artiste($compositeur["idA"],$currentArtiste['nomA']);
                    $currentAlbum->ajouterCompositeur($currentCompositeur);
                }
            }

            // Récupérer les interprètes
            $interpretes = $crudInterpreter->obtenirInterpretesParAlbum($album["id"]);
            if (sizeof($interpretes) == 0) {
                $currentInterprete = new Artiste(-1, "Inconnu");
                $currentAlbum->ajouterInterprete($currentInterprete);
            }
            else {
                foreach ($interpretes as $interprete) {
                    $currentArtiste = $crudArtiste->obtenirArtisteParId($interprete["idA"]);
                    $currentInterprete = new Artiste($interprete["idA"],$currentArtiste['nomA']);
                    $currentAlbum->ajouterInterprete($currentInterprete);
                }
            }

            // Récupérer les genres
            $genres = $crudEtre->obtenirGenresParMusique($album["id"]);
            if (sizeof($genres) == 0) {
                $currentGenre = new Genre(-1, "Inconnu");
                $currentAlbum->ajouterGenre($currentGenre);
            }
            else {
                foreach ($genres as $genre) {
                    $currentGenre = $crudGenre->obtenirGenreParId($genre["idG"]);
                    $currentGenre = new Genre($genre["idG"],$currentGenre['nomG']);
                    $currentAlbum->ajouterGenre($currentGenre);
                }
            }

            $allAlbumsObject[] = $currentAlbum;
        }
    }
    else if ($_GET['table'] == "ARTISTES") {
        $crudArtiste = new CrudArtiste($db::obtenir_connexion());

        $allArtistes = $crudArtiste->obtenirTousArtistes();
        $allArtistesObject = [];

        foreach ($allArtistes as $artiste) {
            $currentArtistes = new Artiste($artiste["idA"], $artiste["nomA"]);
            $allArtistesObject[] = $currentArtistes;
        }
    }
    else if ($_GET['table'] == "GENRES") {
        $crudGenre = new CrudGenre($db::obtenir_connexion());
        $allGenres = $crudGenre->obtenirTousGenres();
        $allGenresObject = [];

        foreach ($allGenres as $genre) {
            $currentGenre = new Genre($genre["idG"], $genre["nomG"]);
            $allGenresObject[] = $currentGenre;
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
        <form action="/App/Views/Admin/Details/Add/PanelAddUser.php?add=UTILISATEUR" method="POST">
            <button class="custom-add-button">
                <p class="add-paragraph">
                    Ajouter un utilisateur
                    <img class="icon-ajouter" src="/Public/Icons/add.png">
                </p>
            </button>
        </form>
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
                            <form action="Update/PanelUpdateUser.php?update=UTILISATEUR" method="post">
                                <input type="hidden" name="user_id" value="<?= $user->getId() ?>">
                                <input type="submit" class="custom-update-submit-button">
                            </form>
                            <form action="Delete/PanelDeleteUser.php?delete=UTILISATEUR" method="post">
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
        <form action="/App/Views/Admin/Details/Add/PanelAddAlbums.php?add=ALBUMS" method="POST">
            <button class="custom-add-button">
                <p class="add-paragraph">
                    Ajouter un album
                    <img class="icon-ajouter" src="/Public/Icons/add.png">
                </p>
            </button>
        </form>
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
                            <form action="Update/PanelUpdateAlbum.php?update=ALBUMS" method="post">
                                <input type="hidden" name="album_id" value="<?= $album->getId() ?>">
                                <input type="submit" class="custom-update-submit-button">
                            </form>
                            <form action="Delete/PanelDeleteAlbum.php?delete=ALBUMS" method="post">
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

    <?php if (isset($_GET['table']) && ($_GET['table'] == "ARTISTES")) : ?>
    <section>
        <form action="/App/Views/Admin/Details/Add/PanelAddArtiste.php?add=ARTISTES" method="POST">
            <button class="custom-add-button">
                <p class="add-paragraph">
                    Ajouter un artiste
                    <img class="icon-ajouter" src="/Public/Icons/add.png">
                </p>
            </button>
        </form>
        <h1>Liste de tous les artistes</h1>
        <table border='1'>
            <tr>
                <th>ID Artiste</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($allArtistesObject as $artiste): ?>
                <tr>
                    <td><?= $artiste->getId() ?></td>
                    <td><?= $artiste->getNomArtiste() ?></td>
                    <td>
                        <div class="form-inline">
                            <form action="Update/PanelUpdateArtiste.php?update=ARTISTES" method="post">
                                <input type="hidden" name="artiste_id" value="<?= $artiste->getId() ?>">
                                <input type="submit" class="custom-update-submit-button">
                            </form>
                            <form action="Delete/PanelDeleteArtiste.php?delete=ARTISTES" method="post">
                                <input type="hidden" name="artiste_id" value="<?= $artiste->getId() ?>">
                                <input type="submit" class="custom-delete-submit-button">
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    </section>
    <?php endif; ?>

    <?php if (isset($_GET['table']) && ($_GET['table'] == "GENRES")) : ?>
    <section>
        <form action="/App/Views/Admin/Details/Add/PanelAddGenre.php?add=GENRES" method="POST">
            <button class="custom-add-button">
                <p class="add-paragraph">
                    Ajouter un genre
                    <img class="icon-ajouter" src="/Public/Icons/add.png">
                </p>
            </button>
        </form>
        <h1>Liste de tous les genres</h1>
        <table border='1'>
            <tr>
                <th>ID Genre</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($allGenresObject as $genre): ?>
                <tr>
                    <td><?= $genre->getId() ?></td>
                    <td><?= $genre->getNomGenre() ?></td>
                    <td>
                        <div class="form-inline">
                            <form action="Update/PanelUpdateGenre.php?update=GENRES" method="post">
                                <input type="hidden" name="genre_id" value="<?= $genre->getId() ?>">
                                <input type="submit" class="custom-update-submit-button">
                            </form>
                            <form action="Delete/PanelDeleteGenre.php?delete=GENRES" method="post">
                                <input type="hidden" name="genre_id" value="<?= $genre->getId() ?>">
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
