<?php 

class DAO 
{
    public $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function choixPersonnage() {
        echo "\033[2J\033[;H";

        echo "Voici la liste des personnages\n\n";
        $sql = $this->db->prepare("SELECT * FROM personnages");
        $sql->execute();
        $result = $sql->fetchAll();

        foreach($result as $personnage) {
            echo $personnage["id"] . ". " . $personnage["nom"] . "\n";
        }

        if (count($result) == 0) {
            echo "Aucun personnage\n";
        }

        $choix = readline();

        switch($choix) {
            case 1:
                $personnage = $this->getPersonnage(1);
                break;
            case 2:
                $personnage = $this->getPersonnage(2);
                break;
            case 3:
                $personnage = $this->getPersonnage(3);
                break;
            case 4:
                $personnage = $this->getPersonnage(4);
                break;
            default:
                echo "Ce personnage n'existe pas\n";
                exit;
        }

        return $personnage;
    }

    public function getPersonnage($id) {
        $sql = $this->db->prepare("SELECT * FROM personnages WHERE id = :id");
        $sql->execute([
            "id" => $id
        ]);
    
        $result = $sql->fetch(PDO::FETCH_CLASS, 'Personnage', array());
    
        if ($result === false) {
            return null;
        }
    
        echo "\033[2J\033[;H";

        echo "ID : " . $result["id"] . "\n";

        echo "Vous avez choisi " . $result["nom"] . "\n";
        return $result;
    }

    function combat(Personnage $personnage, Monstre $monstre) {
        while ($personnage->pointDeVie > 0 && $monstre->pointDeVie > 0) {
            $degats = $personnage->PA - $monstre->PD;
            if ($degats > 0) {
                $monstre->pointDeVie -= $degats;
            } else {
                $personnage->pointDeVie -= $monstre->PA - $personnage->PD;
            }
        }
        if ($personnage->pointDeVie > 0) {
            $personnage->experience += $monstre->experience;
            echo "Vous avez gagné le combat.\n";
        } else {
            echo "Vous avez perdu le combat.\n";
        }
    } 

    public function afficherInventairePersonnage($personnageId) {
        $sql = $this->db->prepare("
            SELECT p.id, p.nom AS nom_personnage, o.nom AS nom_objet
            FROM personnages p
            LEFT JOIN inventaire_personnage ip ON p.id = ip.personnage_id
            LEFT JOIN objet o ON ip.objet_id = o.id
            WHERE p.id = :id
        ");
        $sql->execute([
            "id" => $personnageId
        ]);
        return $sql->fetchAll();
    }

    public function afficherInventaire(Personnage $personnage) {
        echo "Inventaire de " . $personnage->getNom() . " :\n";
        $sql = $this->db->prepare("
            SELECT p.id, p.nom AS nom_personnage, o.nom AS nom_objet
            FROM personnages p
            LEFT JOIN inventaire_personnage ip ON p.id = ip.personnage_id
            LEFT JOIN objet o ON ip.objet_id = o.id
            WHERE p.id = :id
        ");
        $sql->execute([
            "id" => $personnageId
        ]);
        $inventaire = $sql->fetchAll();


        if (count($inventaire) > 0) {
            foreach ($inventaire as $objet) {
                echo "- " . $objet["nom"] . "\n";
            }
        } else {
            echo "L'inventaire est vide.\n";
        }
    }
}
?>