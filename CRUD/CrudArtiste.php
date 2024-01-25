<?php 

class CrudArtiste {

    private $db;

    /**
     * Constructeur de la classe CrudArtiste.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $db La connexion à la base de données.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Ajoute un nouvel artiste à la base de données.
     *
     * @param array $artisteData Les données de l'artiste à ajouter.
     * @return bool True si l'ajout est réussi, False sinon.
     */
    public function ajouterArtiste(array $artisteData) {
        try {
            $query = "INSERT INTO ARTISTES (nomA) VALUES (?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$artisteData['nomA']]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime un artiste de la base de données en fonction de son ID.
     *
     * @param int $artisteId L'ID de l'artiste à supprimer.
     * @return bool True si la suppression est réussie, False sinon.
     */
    public function supprimerArtiste(int $artisteId) {
        try {
            $query = "DELETE FROM ARTISTES WHERE idA = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$artisteId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Modifie les données d'un artiste dans la base de données en fonction de son ID.
     *
     * @param int $artisteId L'ID de l'artiste à modifier.
     * @param array $newArtisteData Les nouvelles données de l'artiste.
     * @return bool True si la modification est réussie, False sinon.
     */
    public function modifierArtiste(int $artisteId, array $newArtisteData) {
        try {
            $query = "UPDATE ARTISTES SET nomA = ? WHERE idA = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$newArtisteData['nomA'], $artisteId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère tous les artistes de la base de données.
     *
     * @return array Un tableau contenant tous les artistes.
     */
    public function obtenirTousArtistes() {
        $query = "SELECT * FROM ARTISTES";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un artiste en fonction de son ID.
     *
     * @param int $artisteId L'ID de l'artiste à récupérer.
     * @return array|false Les données de l'artiste ou False si l'artiste n'est pas trouvé.
     */
    public function obtenirArtisteParId(int $artisteId) {
        $query = "SELECT * FROM ARTISTES WHERE idA = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$artisteId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }
}


?>