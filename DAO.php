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
                $salleId = $this->choisirSalleAleatoire();
                $this->mettreAJourSalleActuelle($personnage['id'], $salleId);
                $this->afficherInfosSalle($salleId, $personnage['id']);
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
    private function getMonstre($monstreId) {
        $sql = $this->db->prepare("SELECT * FROM monstres WHERE id = :monstreId");
        $sql->execute([
            "monstreId" => $monstreId,
        ]);
        $monstre = $sql->fetch();
    
        return $monstre;
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
    private function choisirSalleAleatoire() {
        $sql = $this->db->prepare("SELECT id FROM salles ORDER BY RAND() LIMIT 1");
        $sql->execute();
        $result = $sql->fetch();
    
        return $result['id'];
    }
    
    private function mettreAJourSalleActuelle($personnageId, $salleId) {
        $sql = $this->db->prepare("UPDATE personnages SET salle_actuelle = :salleId WHERE id = :personnageId");
        $sql->execute([
            "salleId" => $salleId,
            "personnageId" => $personnageId,
        ]);
    }
    private function afficherInfosSalle($salleId, $personnageId) {
        $sql = $this->db->prepare("SELECT * FROM salles WHERE id = :salleId");
        $sql->execute([
            "salleId" => $salleId,
        ]);
        $result = $sql->fetch();
    
        if ($result === false) {
            echo "Salle introuvable.\n";
            return;
        }
    
        echo "Informations de la salle :\n";
        echo "Nom : " . $result["nom"] . "\n";
        echo "Description : " . $result["description"] . "\n";
    
        switch ($result["type"]) {
            case "Monstre":
                $monstreId = $this->getMonstreId($salleId);
                $this->gestionCombat($personnageId, $result["monstre_id"]);
                break;
            case "Marchand":
                $this->afficherOptionsMarchand($personnageId, $result["id"]);
                break;
            case "Piege":
                $this->appliquerEffetPiege($personnageId);
                break;
            case "Questions":
                $this->afficherQuestion($result["id"]);
                break;
            default:
                echo "Type de salle non reconnu.\n";
                break;
        }
    }
    private function getMonstreId($salleId) {
        $sql = $this->db->prepare("SELECT monstre_id FROM salles WHERE id = :salleId");
        $sql->execute([
            "salleId" => $salleId,
        ]);
        $result = $sql->fetch();

        return $result ? $result['monstre_id'] : null;
    }
    private function afficherOptionsMarchand($personnageId, $salleId) {
        echo "Vous entrez dans la salle d'un marchand !\n";

    }
    
    private function afficherQuestion($salleId) {
        echo "Vous entrez dans une salle de questions !\n";

    }
    
    private function appliquerEffetPiege($personnageId) {
        echo "Vous entrez dans une salle de piège !\n";
        echo "Vous perdez 10 points de vie !\n";
    
        $sql = $this->db->prepare("UPDATE personnages SET points_de_vie = points_de_vie - 10 WHERE id = :personnageId");
        $sql->execute([
            "personnageId" => $personnageId,
        ]);
    }
    
    private function gestionCombat($personnageId, $monstreId) {
        echo "Vous entrez en combat avec un monstre !\n";
        $personnage = $this->getPersonnage($personnageId);
    
        $sql = $this->db->prepare("SELECT * FROM monstres WHERE id = :monstreId");
        $sql->execute([
            "monstreId" => $monstreId,
        ]);
        $monstre = $sql->fetch();

        while ($personnage['points_de_vie'] > 0 && $monstre['points_de_vie'] > 0) {
            echo "Options de combat:\n";
            echo "1. Attaquer\n";
            echo "2. Se défendre\n";
    
            $choixCombat = readline();
    
            switch ($choixCombat) {
                case 1:
                    $degatsJoueur = max(0, $personnage['points_d_attaque']);
                    $sql = $this->db->prepare("UPDATE monstres SET points_de_vie = points_de_vie - :degats WHERE id = :monstreId");
                    $sql->execute([
                        "degats" => $degatsJoueur,
                        "monstreId" => $monstreId,
                    ]);
    
                    echo "Vous avez infligé $degatsJoueur points de dégâts au monstre!\n";
                    break;
                case 2:
                    $degatsMonstre = max(0, $monstre['points_d_attaque'] - $personnage['points_de_defense']);
                    $sql = $this->db->prepare("UPDATE personnages SET points_de_vie = points_de_vie - :degats WHERE id = :personnageId");
                    $sql->execute([
                        "degats" => $degatsMonstre,
                        "personnageId" => $personnageId,
                    ]);
    
                    echo "Vous avez subi $degatsMonstre points de dégâts!\n";
                    break;
                default:
                    echo "Choix invalide. Veuillez choisir 1 ou 2.\n";
            }
            $personnage = $this->getPersonnage($personnageId);
            $monstre = $this->getMonstre($monstreId);
            echo "Points de vie restants du personnage : {$personnage['points_de_vie']}\n";
            echo "Points de vie restants du monstre : {$monstre['points_de_vie']}\n";
    
            if ($monstre['points_de_vie'] > 0) {
                $degatsMonstre = max(0, $monstre['points_d_attaque']);
    
                $sql = $this->db->prepare("UPDATE personnages SET points_de_vie = points_de_vie - :degats WHERE id = :personnageId");
                $sql->execute([
                    "degats" => $degatsMonstre,
                    "personnageId" => $personnageId,
                ]);
    
                //$personnage = $this->getPersonnage($personnageId);
                echo "Le monstre vous a infligé $degatsMonstre points de dégâts!\n";
                echo "Points de vie restants du personnage : {$personnage['points_de_vie']}\n";
            }
        }
        if ($personnage['points_de_vie'] > 0) {
            echo "Vous avez vaincu le monstre!\n";
        } else {
            echo "Vous avez été vaincu par le monstre!\n";
        }
    }
}

?>