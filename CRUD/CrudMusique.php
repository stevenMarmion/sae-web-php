<?php

class CrudMusique {

    private $db;

    /**
     * Constructeur de la classe CrudMusique.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $db La connexion à la base de données.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Ajoute une nouvelle musique à la base de données.
     *
     * @param array $musiqueData Les données de la musique à ajouter.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterMusique(array $musiqueData) {
        try {
            $query = "INSERT INTO MUSIQUES (img, dateDeSortie, titre, idCompositeur, idInterprete) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$musiqueData['img'], $musiqueData['dateDeSortie'], $musiqueData['titre'], $musiqueData['idCompositeur'], $musiqueData['idInterprete']]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime une musique de la base de données en fonction de son ID.
     *
     * @param int $musiqueId L'ID de la musique à supprimer.
     * @return bool True si la suppression est réussie, False sinon.
     */
    public function supprimerMusique(int $musiqueId) {
        try {
            $query = "DELETE FROM MUSIQUES WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$musiqueId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Modifie les données d'une musique dans la base de données en fonction de son ID.
     *
     * @param int $musiqueId L'ID de la musique à modifier.
     * @param array $newMusiqueData Les nouvelles données de la musique.
     * @return bool True si la modification est réussie, False sinon.
     */
    public function modifierMusique(int $musiqueId, array $newMusiqueData) {
        try {
            $query = "UPDATE MUSIQUES SET img = ?, dateDeSortie = ?, titre = ?, idCompositeur = ?, idInterprete = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$newMusiqueData['img'], $newMusiqueData['dateDeSortie'], $newMusiqueData['titre'], $newMusiqueData['idCompositeur'], $newMusiqueData['idInterprete'], $musiqueId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère toutes les musiques de la base de données.
     *
     * @return array Un tableau contenant toutes les musiques.
     */
    public function obtenirToutesMusiques() {
        $query = "SELECT * FROM MUSIQUES";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une musique en fonction de son ID.
     *
     * @param int $musiqueId L'ID de la musique à récupérer.
     * @return array|false Les données de la musique ou False si la musique n'est pas trouvée.
     */
    public function obtenirMusiqueParId(int $musiqueId) {
        $query = "SELECT * FROM MUSIQUES WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$musiqueId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }
}


?>