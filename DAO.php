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
                $this->afficherInfosPersonnage(1);
                break;
            case 2:
                $personnage = $this->getPersonnage(2);
                $this->afficherInventaire(2);
                break;
            case 3:
                $personnage = $this->getPersonnage(3);
                $this->afficherInventaire(3);
                break;
            default:
                echo "Ce personnage n'existe pas\n";
                exit;
        }

        $sql = $this->db->prepare("SELECT * FROM personnages WHERE id = :id");
        $sql->execute([
            "id" => $choix
        ]);

        return;
    }

    public function getPersonnage($id) {
        $sql = $this->db->prepare("SELECT * FROM personnages WHERE id = :id");
        $sql->execute([
            "id" => $id
        ]);
        $result = $sql->fetch();
        if ($result === false) {
            return null;
        }

        echo "\033[2J\033[;H";

        echo "ID : " . $result["id"] . "\n";

        echo "Vous avez choisi " . $result["nom"] . "\n";
        return $result;
    }

    // function combat(Personnage $personnage, Monstre $monstre) {
    //     while ($personnage->getPointDeVie() > 0 && $monstre->getPointDeVie() > 0) {
    //         $degats = $personnage->PA - $monstre->PD;
    //         if ($degats > 0) {
    //             $monstre->pointDeVie -= $degats;
    //         } else {
    //             $personnage->pointDeVie -= $monstre->PA - $personnage->PD;
    //         }
    //     }
    //     if ($personnage->pointDeVie > 0) {
    //         $personnage->experience += $monstre->experience;
    //         echo "Vous avez gagné le combat.\n";
    //     } else {
    //         echo "Vous avez perdu le combat.\n";
    //     }
    // } 

    public function afficherInventaire($id) {
        echo "Voici votre inventaire :\n";
        $sql = $this->db->prepare("SELECT ip.id, o.nom FROM inventaire_personnage ip JOIN objet o ON ip.objet_id = o.id WHERE ip.personnage_id = :id");
        $sql->execute([
            "id" => $id
        ]);
        $result = $sql->fetchAll();

        foreach($result as $item) {
            echo $item["id"] . ". " . $item["nom"] . "\n";
        }

        if (count($result) == 0) {
            echo "Aucun item\n";
        }
    }
    public function afficherInfosPersonnage($id) {
        echo "\033[2J\033[;H";
    
        $sql = $this->db->prepare("SELECT * FROM personnages WHERE id = :id");
        $sql->execute([
            "id" => $id
        ]);
    
        $result = $sql->fetch();
    
        if ($result === false) {
            echo "Personnage introuvable.\n";
            return;
        }
    
        echo "Informations du personnage :\n";
        echo "Nom : " . $result["nom"] . "\n";
        echo "Points de vie : " . $result["points_de_vie"] . "\n";
        echo "Points d'attaque : " . $result["points_d_attaque"] . "\n";
        echo "Points de défense : " . $result["points_de_defense"] . "\n";
        echo "Expérience : " . $result["experience"] . "\n";
        echo "Niveau : " . $result["niveau"] . "\n";

        $this->afficherInventaire($id);
    }
    
}

?>