<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;

Autoloader::register();

function rechercheNomAlbum(string $nomAlbum){
    
}

function rechercheNomArtiste(string $nomArt){

}

function rechercheAnnee(int $annee){

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['filtre']) && isset($_POST['recherche'])) {
        if($_POST["filtre"]=="nomAlbum"){
            rechercheNomAlbum($_POST['recherche']);
        }
        if($_POST["filtre"]=="nomArtiste"){
            rechercheNomArtiste($_POST['recherche']);
        }
        if($_POST["filtre"]=="Annee"){
            rechercheAnnee($_POST['recherche']);
        }
    } else {
        header('Location: /App/Views/Playlist/Playlists.php?error=2');
        exit();
    }
}

?>