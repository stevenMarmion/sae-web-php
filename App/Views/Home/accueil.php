<?php
require_once '../../Autoloader/autoloader.php';

use App\Autoloader\Autoloader;
use App\Models\EntityOperations\CrudAlbum;
use App\Models\EntityOperations\CrudArtiste;
use Database\DatabaseConnection\ConnexionBDD;
use App\Models\Album;
use App\Views\Base\head;
use App\Views\Base\footer;
use App\Models\EntityOperations\CrudGenre;

require_once '../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once '../../Models/EntityOperations/CrudAlbum.php';
require_once '../../Models/EntityOperations/CrudArtiste.php';
require_once '../../Models/EntityOperations/CrudGenre.php';
require_once '../../Models/Album.php';

Autoloader::register();

$instance = new ConnexionBDD();
$crudAlbum = new CrudAlbum($instance::obtenir_connexion());
$crudArtiste = new CrudArtiste($instance::obtenir_connexion());
$listeAlbum = $crudAlbum->obtenirTousAlbums();
$listeAlbumObjet = [];
foreach ($listeAlbum as $album) {
    $idC = $crudAlbum->obtenirCompositeurId(intval($album["id"]))["idA"];
    $idI = $crudAlbum->obtenirInterpreteId(intval($album["id"]))["idA"];
    $listeGenre = $crudAlbum->obtenirGenresAlbum($album["id"]);
    $al = new Album($idM=intval($album["id"]),
                    $img=$album["img"] ?? "",
                    $dateDeSortie=intval($album["dateDeSortie"]),
                    $title=$album["titre"],
                    $compositeur=$crudArtiste->obtenirArtisteParId($idC),
                    $interprete=$crudArtiste->obtenirArtisteParId($idI),
                    $genre=$listeGenre === false ? [] : $listeGenre,
                );
    array_push($listeAlbumObjet, $al);
}
include_once '../Base/head.php';
?>
    <div class="nav">
        <a href="#">Mes playlists</a>
        <a href="#">Recherche utilisateur</a>
    </div>
    <h1> Voici les dernieres sorties ! </h1>
    <ul>
    <?php
    foreach($listeAlbumObjet as $album){
        if($album->getCompositeur()["nomA"]==$album->getInterprete()["nomA"]){
            echo '<li class="album"><img src="../../../DataRessources/images/'.$album->getImg().'" alt="#"> <h4 class="titreAlbum">'.$album->getTitre()."</h4> <div class='interprete&compositeur'>interprete et compositeur : ".$album->getCompositeur()["nomA"].'</div> ';
        }
        else{
            echo "<li class='album'><img src='../../../DataRessources/images/".$album->getImg()."' alt='image album'> <h4 class='titreAlbum'>".$album->getTitre()."</h4> <div class='compositeur'> compositeur : ".$album->getCompositeur()["nomA"]." </div> <div class='interprete'> interprete : ".$album->getInterprete()["nomA"].'</div>';
        }
        echo "<a href='/App/Views/Details/detailAlbum.php?id=".$album->getId()."'><button>voir plus</button></a></li>";
    }
    ?>
    </ul>
    <div class="infoAlbum" hidden>
    </div>
    <script src="../../../Public/JS/affichageInfo.js"></script>
<?php
include_once '../Base/footer.php';
?>