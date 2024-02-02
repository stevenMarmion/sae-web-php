<?php

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';
require_once __DIR__ . '/CrudArtiste.php';
require_once __DIR__ . '/CrudGenre.php';
require_once __DIR__ . '/CrudComposer.php';
require_once __DIR__ . '/CrudInterprete.php';
require_once __DIR__ . '/CrudEtre.php';

use \App\Models\EntityOperations\CrudArtiste;
use \App\Models\EntityOperations\CrudGenre;
use \App\Models\EntityOperations\CrudComposer;
use \App\Models\EntityOperations\CrudInterprete;
use \App\Models\EntityOperations\CrudEtre;
use \App\Autoloader\Autoloader;
use \App\Models\Album;
use PDO;
use PDOException;

Autoloader::register();

class CrudAlbum {

    private $db;

    /**
     * Constructeur de la classe CrudAlbum.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $db La connexion à la base de données.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Ajoute un nouvel album à la base de données depuis une donnée yml.
     *
     * @param array $albumData Les données de l'album à ajouter.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterAlbumFromYml(array $albumData) {
        try {
            $query = "INSERT INTO ALBUMS (img, dateDeSortie, titre) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$albumData['img'], $albumData['dateDeSortie'], $albumData['titre']]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Ajoute un nouvel album à la base de données depuis une donnée Album en objet.
     *
     * @param Album $albumData Les données de l'album à ajouter.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterAlbumFromObject(Album $albumData) {
        $crudComposer = new CrudComposer($this->db);
        $crudInterpreter = new CrudInterprete($this->db);
        $crudEtre = new CrudEtre($this->db);

        try {

            $query = "SELECT * FROM ALBUMS WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$albumData->getId()]); 
            $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;

            if ($stmt != false) {
                $query = "INSERT INTO ALBUMS (id, img, dateDeSortie, titre) VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$albumData->getId(), $albumData->getImg(), $albumData->getDateSortie(), $albumData->getTitre()]);

                // Ajoute le(s) compositeur(s) de l'album
                foreach ($albumData->getCompositeurs() as $compositeur) {
                    print_r($compositeur);
                    $crudComposer->ajouterCompositeur($albumData->getId(), $compositeur);
                }

                // Ajoute les interprètes de l'album
                foreach ($albumData->getInterpretes() as $interprete) {
                    print_r($interprete);
                    $crudInterpreter->ajouterInterprete($albumData->getId(), $interprete);
                }

                // Ajoute le(s) genre(s) de l'album
                foreach ($albumData->getGenres() as $genre) {
                    print_r($genre);
                    $crudEtre->ajouterRelation($albumData->getId(), $genre);
                }
            }
            else {
                return false;
            }

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime un album de la base de données en fonction de son ID.
     *
     * @param int $albumId L'ID de l'album à supprimer.
     * @return bool True si la suppression est réussie, False sinon.
     */
    public function supprimerAlbum(int $albumId) {
        try {
            $query = "DELETE FROM ALBUMS WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$albumId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Modifie les données d'un album dans la base de données en fonction de son ID.
     *
     * @param int $albumId L'ID de l'album à modifier.
     * @param Album $newAlbumData Les nouvelles données de l'album.
     * @return bool True si la modification est réussie, False sinon.
     */
    public function modifierAlbum(int $albumId, Album $newAlbumData, array $ancienComp, array $ancienInt, array $ancienGenres) {
        $crudArtiste = new CrudArtiste($this->db);
        $crudGenre = new CrudGenre($this->db);

        try {
            // Modifier les données de l'album
            $query = "UPDATE ALBUMS SET img = ?, dateDeSortie = ?, titre = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$newAlbumData->getImg(), 
                            $newAlbumData->getDateSortie(), 
                            $newAlbumData->getTitre(),
                            $albumId]);

            // Modifier le(s) compositeur(s) de l'album
            foreach ($ancienComp as $index_compositeur => $compositeur) {
                $query = "UPDATE COMPOSER SET idA = ? WHERE idAl = ? and idA = ?";
                $stmt = $this->db->prepare($query);
                $idArtiste = $crudArtiste->obtenirArtisteParNom($newAlbumData->getCompositeurs()[$index_compositeur])["idA"];
                $stmt->execute([$idArtiste,
                                $albumId,
                                $compositeur]);
            }

            // Modifier les interprètes de l'album
            foreach ($ancienInt as $index_interprete => $interprete) {
                $query = "UPDATE INTERPRETER SET idA = ? WHERE idAl = ? and idA = ?";
                $stmt = $this->db->prepare($query);
                $idArtiste = $crudArtiste->obtenirArtisteParNom($newAlbumData->getInterpretes()[$index_interprete])["idA"];
                $stmt->execute([$idArtiste,
                                $albumId,
                                $interprete]);
            }

            // Modifier le(s) genre(s) de l'album
            foreach ($ancienGenres as $index_genre => $genre) {
                $query = "UPDATE ETRE SET idG = ? WHERE idAl = ? and idG = ?";
                $stmt = $this->db->prepare($query);
                $idGenre = $crudGenre->obtenirGenreParId($newAlbumData->getGenres()[$index_genre])["idG"];
                $stmt->execute([$idGenre,
                                $albumId,
                                $genre]);
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère tous les albums de la base de données.
     *
     * @return array Un tableau contenant tous les albums.
     */
    public function obtenirTousAlbums() {
        $query = "SELECT * FROM ALBUMS";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un album en fonction de son ID.
     *
     * @param int $albumId L'ID de l'album à récupérer.
     * @return array|false Les données de l'album ou False si l'album n'est pas trouvé.
     */
    public function obtenirAlbumParId(int $albumId) {
        $query = "SELECT * FROM ALBUMS WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$albumId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }
}

?>
