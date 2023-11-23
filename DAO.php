<?php
class DAO 
{
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function choixPersonnage() {
        // Implémentation de la logique pour choisir un personnage
        echo "Choisissez un personnage par son ID :\n";
        $personnages = $this->getAllPersonnages();
        foreach ($personnages as $personnage) {
            echo "{$personnage['id']}. {$personnage['nom']}\n";
        }

        $choix = readline("Entrez l'ID du personnage : ");
        $personnage = $this->getPersonnageById($choix);

        return $personnage;
    }

    public function chargerPartie($personnageId) {
        // Implémentation de la logique pour charger une partie
        $personnage = $this->getPersonnageById($personnageId);

        return $personnage;
    }

    public function afficherInventaire($personnageId) {
        $inventaire = $this->getInventaireByPersonnageId($personnageId);
        echo "Inventaire du personnage :\n";
        $inventaire = $dao->getInventaireByPersonnageId($this->id);
        echo "Inventaire du personnage {$this->nom} :\n";
        foreach ($inventaire as $objet) {
            echo "{$objet['objet_id']}. {$objet['nom_objet']}\n";
        }

    }

    private function getAllPersonnages() {
        // Récupère tous les personnages de la base de données
        $query = "SELECT * FROM personnages";
        $result = $this->db->query($query);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getPersonnageById($personnageId) {
        // Récupère un personnage par son ID
        $query = "SELECT * FROM personnages WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $personnageId);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            return new Personnage(
                $result['id'],
                $result['nom'],
                $result['points_de_vie'],
                $result['points_d_attaque'],
                $result['points_de_defense'],
                $result['experience'],
                $result['niveau']
            );
        } else {
            return null;
        }
    }
    

    private function getInventaireByPersonnageId($personnageId) {
        // Récupère l'inventaire d'un personnage par son ID
        $query = "SELECT ip.objet_id, o.nom_objet FROM inventaire_personnage ip
                  JOIN objets o ON ip.objet_id = o.id
                  WHERE ip.personnage_id = :personnage_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':personnage_id', $personnageId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
