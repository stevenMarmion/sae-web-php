<?php

declare(strict_types=1);

namespace App\Models\EntityOperations;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

use \App\Models\EntityOperations\CrudComposer;
use \App\Models\EntityOperations\CrudInterprete;
use \App\Models\EntityOperations\CrudEtre;
use \App\Models\EntityOperations\CrudContenir;
use \App\Models\EntityOperations\CrudNote;
use \App\Models\EntityOperations\CrudFavoris;
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
        try {
            $query = "SELECT * FROM ALBUMS WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$albumData->getId()]); 
            $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;

            if ($stmt != false) {
                $crudComposer = new CrudComposer($this->db);
                $crudInterpreter = new CrudInterprete($this->db);
                $crudEtre = new CrudEtre($this->db);


                $query = "INSERT INTO ALBUMS (id, img, dateDeSortie, titre) VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$albumData->getId(), $albumData->getImg(), $albumData->getDateSortie(), $albumData->getTitre()]);

                // Ajoute le(s) compositeur(s) de l'album
                foreach ($albumData->getCompositeurs() as $compositeur) {
                    $crudComposer->ajouterCompositeur($albumData->getId(), intval($compositeur));
                }

                // Ajoute les interprètes de l'album
                foreach ($albumData->getInterpretes() as $interprete) {
                    $crudInterpreter->ajouterInterprete($albumData->getId(), intval($interprete));
                }

                // Ajoute le(s) genre(s) de l'album
                foreach ($albumData->getGenres() as $genre) {
                    $crudEtre->ajouterRelation($albumData->getId(), intval($genre));
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
            $crudComposer = new CrudComposer($this->db);
            $crudInterpreter = new CrudInterprete($this->db);
            $crudEtre = new CrudEtre($this->db);
            $crudContenir = new CrudContenir($this->db);
            $crudNote = new CrudNote($this->db);
            $crudFavoris = new CrudFavoris($this->db);

            $crudContenir->supprimerAllAlbum($albumId);
            $crudNote->supprimerToutesNotesFromIdAlbum($albumId);
            $crudFavoris->supprimerAlbumFromFavori($albumId);
            $crudEtre->supprimerAllAlbum($albumId);
            $crudInterpreter->supprimerAllAlbum($albumId);
            $crudComposer->supprimerAllAlbum($albumId);

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
        try {
            $crudComposer = new CrudComposer($this->db);
            $crudInterpreter = new CrudInterprete($this->db);
            $crudEtre = new CrudEtre($this->db);

            // Modifier les données de l'album
            $query = "UPDATE ALBUMS SET img = ?, dateDeSortie = ?, titre = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$newAlbumData->getImg(), 
                            $newAlbumData->getDateSortie(), 
                            $newAlbumData->getTitre(),
                            $albumId]);

            // Modifier le(s) compositeur(s) de l'album
            foreach ($ancienComp as $indexComp => $compositeur) {
                $crudComposer->modifierCompositeur($albumId, $newAlbumData, $indexComp, intval($compositeur)); // compositeur = 3, indexComp = 1
            }

            // Modifier les interprètes de l'album
            foreach ($ancienInt as $indexInt => $interprete) {
                $crudInterpreter->modifierInterprete($albumId, $newAlbumData, $indexInt, intval($interprete));
            }

            // Modifier le(s) genre(s) de l'album
            foreach ($ancienGenres as $indexGenre => $genre) {
                $crudEtre->modifierRelation($albumId, $newAlbumData, $indexGenre, intval($genre));
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

    public function obtenirGenresAlbum(int $albumId) {
        $query = "SELECT * FROM GENRE WHERE idG in (SELECT idG FROM ETRE WHERE idAl=? )";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$albumId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;
    }

    public function obtenirCompositeurId(int $albumId) {
        $query = "SELECT idA FROM COMPOSER WHERE idAl=?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$albumId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }

    public function obtenirInterpreteId(int $albumId) {
        $query = "SELECT idA FROM INTERPRETER WHERE idAl=?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$albumId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        
    }

    public function obtenirAlbumsParDerniereSortie() {
        $query = "SELECT * FROM ALBUMS ORDER BY dateDeSortie DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
