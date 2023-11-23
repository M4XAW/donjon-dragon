<?php
class DAO 
{
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function choixPersonnage() {
        echo "\033[2J\033[;H";

        echo "Voici la liste des personnages\n\n";
        $sql = $this->db->prepare("SELECT * FROM personnages");
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_CLASS, 'Personnage', array());

        foreach($result as $personnage) {
            echo $personnage->getId() . ". " . $personnage->getNom() . "\n";
        }

        if (count($result) == 0) {
            echo "Aucun personnage\n";
        }

        $choix = readline();

        foreach($result as $personnage) {
            if ($personnage->getId() == $choix) {
                echo "\033[2J\033[;H";
                echo "Vous avez choisi " . $personnage->getNom() . "\n";
                $personnage->afficherInventaire($this);
                return $personnage;
            }
        }

        echo "Ce personnage n'existe pas\n";
        exit;
    }
    
    

    public function afficherInventaire(Personnage $personnage) {
        $query = "
            SELECT o.nom AS nom_objet
            FROM inventaire_personnage ip
            JOIN objet o ON ip.objet_id = o.id
            WHERE ip.personnage_id = {$personnage->getId()}
        ";

        $result = mysqli_query($this->db, $query);
        $inventaire = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $inventaire[] = $row['nom_objet'];
        }

        echo "Inventaire de " . $personnage->getNom() . " :\n";

        if (count($inventaire) > 0) {
            foreach ($inventaire as $objet) {
                echo "- $objet\n";
            }
        } else {
            echo "L'inventaire est vide.\n";
        }
    }
}
