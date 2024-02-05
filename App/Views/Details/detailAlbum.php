
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

if (isset($_GET["id"])) {
    $idAlbum = $_GET["id"];
}


$instance = new ConnexionBDD();
$crudAlbum = new CrudAlbum($instance::obtenir_connexion());
$crudArtiste = new CrudArtiste($instance::obtenir_connexion());
$album = $crudAlbum->obtenirAlbumParId($idAlbum);
$img = $album["img"] == "" ? "base.jpg" : $album["img"];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>acceuil</title>
</head>
<body>

<?php

if(file_exists("../../../DataRessources/images/".$img) && (strstr($img,"%")===false)){
    $img = $album["img"] == "" ? "base.jpg" : $album["img"];
}
else{
    $img = "base.jpg";
}

?>

<h2> <?=$album["titre"]?> </h2>
<img src="<?= '../../../DataRessources/images/'.$img?>" alt='image album' class="imageAlbum">
<h3>Date de sortie : <?$album["dateDeSortie"]?></h3>
<h3>Compositeur : <?=$crudArtiste->obtenirArtisteParId($crudAlbum->obtenirCompositeurId($idAlbum)["idA"])["nomA"]?></h3>
<h3>Interprete : <?=$crudArtiste->obtenirArtisteParId($crudAlbum->obtenirInterpreteId($idAlbum)["idA"])["nomA"]?></h3>
<?php
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
?>
<h3>Genre : <?= $genres ?> </h3>
    <style>
        .imageAlbum{
            width: 200px;
            height: 200px;
        }
    </style>

</body>
</html>