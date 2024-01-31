
<?php

require_once '../../Autoloader/autoloader.php';
use App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudAlbum;
use App\Models\EntityOperations\CrudArtiste;
use Database\DatabaseConnection\ConnexionBDD;
use App\Views\Base\head;
use App\Views\Base\footer;
use App\Models\Album;

Autoloader::register();

require_once '../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once '../../Models/EntityOperations/CrudAlbum.php';
require_once '../../Models/EntityOperations/CrudArtiste.php';
require_once '../../Models/EntityOperations/CrudGenre.php';
require_once '../../Models/Album.php';

$idAlbum = $_GET["id"];


$instance = new ConnexionBDD();
$crudAlbum = new CrudAlbum($instance::obtenir_connexion());
$crudArtiste = new CrudArtiste($instance::obtenir_connexion());
$album = $crudAlbum->obtenirAlbumParId($idAlbum);

include_once '../Base/head.php';

echo "<h2>".$album["titre"]."</h2>";
echo "<img src='../../../DataRessources/images/".$album["img"]."' alt='image album'>";
echo "<h3>Date de sortie : ".$album["dateDeSortie"]."</h3>";
echo "<h3>Compositeur : ".$crudArtiste->obtenirArtisteParId($crudAlbum->obtenirCompositeurId($idAlbum)["idA"])["nomA"]."</h3>";
echo "<h3>Interprete : ".$crudArtiste->obtenirArtisteParId($crudAlbum->obtenirInterpreteId($idAlbum)["idA"])["nomA"]."</h3>";
$genres="";
$taille=sizeof($crudAlbum->obtenirGenresAlbum($idAlbum));
foreach ($crudAlbum->obtenirGenresAlbum($idAlbum) as $genre){
    if($taille==1){
        $genres.=$genre["nomG"];
    }
    else{
        $genres.=$genre["nomG"].", ";
        $taille--;
    }
}
echo "<h3>Genre : ".$genres."</h3>";


include_once '../Base/footer.php';
?>