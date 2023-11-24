<?php

class DAO
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function infligerDegatsPersonnage($personnageId, $degats)
    {
        $sql = $this->db->prepare("UPDATE personnages SET points_de_vie = points_de_vie - :degats WHERE id = :personnageId");
        $sql->execute([
            "degats" => $degats,
            "personnageId" => $personnageId,
        ]);
    }

    public function choixPersonnage()
    {
        while(true) {
            echo "\033[2J\033[;H";

            echo "Voici la liste des personnages\n\n";
            $sql = $this->db->prepare("SELECT * FROM personnages");
            $sql->execute();
            $result = $sql->fetchAll();

            foreach ($result as $personnage) {
                echo $personnage["id"] . ". " . $personnage["nom"] . "\n";
            }

            if (count($result) == 0) {
                echo "Aucun personnage\n";
            }

            echo "\n";

            $choix = readline();

            if ($choix == 1) {
                $personnage = $this->getPersonnage(1);
                $salleId = $this->choisirSalleAleatoire();
                $this->mettreAJourSalleActuelle($personnage['id'], $salleId);
                $this->afficherInfosSalle($salleId, $personnage['id']);
            } else if ($choix == 2) {
                $personnage = $this->getPersonnage(2);
                $salleId = $this->choisirSalleAleatoire();
                $this->mettreAJourSalleActuelle($personnage['id'], $salleId);
                $this->afficherInfosSalle($salleId, $personnage['id']);
            } else if ($choix == 3) {
                $personnage = $this->getPersonnage(3);
                $salleId = $this->choisirSalleAleatoire();
                $this->mettreAJourSalleActuelle($personnage['id'], $salleId);
                $this->afficherInfosSalle($salleId, $personnage['id']);
            } else {
                echo "Personnage introuvable ";
                sleep(1);
                continue;
            }

            break;
        }
    }


    public function getPersonnage($id)
    {
        $sql = $this->db->prepare("SELECT * FROM personnages WHERE id = :id");
        $sql->execute([
            "id" => $id
        ]);
        $result = $sql->fetch();

        return $result;
    }
    private function getMonstre($monstreId)
    {
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

    public function afficherInventaire($id)
    {
        echo "Inventaire : ";
        $sql = $this->db->prepare("SELECT ip.id, o.nom FROM inventaire_personnage ip JOIN objet o ON ip.objet_id = o.id WHERE ip.personnage_id = :id");
        $sql->execute([
            "id" => $id
        ]);
        $result = $sql->fetchAll();

        foreach ($result as $item) {
            echo $item["nom"] . " | ";
        }

        if (count($result) == 0) {
            echo "Aucun item\n";
        }
    }

    public function afficherInfosPersonnage($id)
    {
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

        echo "👤 " . $result["nom"] . " | ❤️  " . $result["points_de_vie"] . " | 🗡️  " . $result["points_d_attaque"] . " | 🛡️  " . $result["points_de_defense"] . " | 🔥 " . $result["experience"] . " | 🎖️  " . $result["niveau"] . "\n\n";
        $this->afficherInventaire($id);
        echo "\n\n";
    }

    private function choisirSalleAleatoire()
    {
        $sql = $this->db->prepare("SELECT id FROM salles ORDER BY RAND() LIMIT 1");
        $sql->execute();
        $result = $sql->fetch();

        return $result['id'];
    }

    private function mettreAJourSalleActuelle($personnageId, $salleId)
    {
        $sql = $this->db->prepare("UPDATE personnages SET salle_actuelle = :salleId WHERE id = :personnageId");
        $sql->execute([
            "salleId" => $salleId,
            "personnageId" => $personnageId,
        ]);
    }
    private function afficherInfosSalle($salleId, $personnageId)
    {
        $sql = $this->db->prepare("SELECT * FROM salles WHERE id = :salleId");
        $sql->execute([
            "salleId" => $salleId,
        ]);
        $result = $sql->fetch();

        if ($result === false) {
            echo "Salle introuvable.\n";
            return;
        }

        echo "\033[2J\033[;H";

        echo "Informations de la salle :\n";
        echo "Nom : " . $result["nom"] . "\n";
        echo "Description : " . $result["description"] . "\n\n";

        switch ($result["type"]) {
            case "Monstre":
                $monstreId = $this->getMonstreId($salleId);
                $this->gestionCombat($personnageId, $result["monstre_id"]);
                break;
            case "Marchand":
                $this->afficherOptionsMarchand($personnageId, $result["id"]);
                break;
            case "Piège":
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
    private function getMonstreId($salleId)
    {
        $sql = $this->db->prepare("SELECT monstre_id FROM salles WHERE id = :salleId");
        $sql->execute([
            "salleId" => $salleId,
        ]);
        $result = $sql->fetch();

        return $result ? $result['monstre_id'] : null;
    }
    private function afficherOptionsMarchand($personnageId, $salleId)
    {
        echo "Vous entrez dans la salle d'un marchand !\n";

    }

    private function afficherQuestion($salleId)
    {
        echo "Vous entrez dans une salle de questions !\n";

    }

    private function appliquerEffetPiege($personnageId)
    {
        echo "Vous entrez dans une salle de piège !\n";

        sleep(2);
        echo "\033[2J\033[;H";

        $positionPiege = rand(1, 2); 

        while(true) {
            echo "Que voulez-vous faire ?\n";
            echo "1. Aller à gauche\n";
            echo "2. Aller à droite\n\n";
    
            $choix = readline();

            if ($choix == $positionPiege) {
                $this->infligerDegatsPersonnage($personnageId, 10);
                echo "Vous êtes tombé dans un piège !\n";
                echo "Vous avez perdu 10 points de vie.\n";
                break;
            } else if ($choix != $positionPiege && ($choix == 1 || $choix == 2)) {
                echo "Vous avez évité le piège !\n";
                break;
            } else {
                echo "\nChoix invalide ! Veuillez choisir 1 ou 2 ";
                sleep(1);
                echo "\033[2J\033[;H";
                continue;
            }

            break;
        }
    }

    private function gestionCombat($personnageId, $monstreId)
    {
        echo "Vous entrez en combat avec un monstre !\n\n";
        sleep(2);
        
        $personnage = $this->getPersonnage($personnageId);

        $sql = $this->db->prepare("SELECT * FROM monstres WHERE id = :monstreId");
        $sql->execute([
            "monstreId" => $monstreId,
        ]);
        $monstre = $sql->fetch();

        while ($personnage['points_de_vie'] > 0 && $monstre['points_de_vie'] > 0) {
            echo "\033[2J\033[;H";
            
            $this->afficherInfosPersonnage($personnageId);


            echo "Options de combat:\n";
            echo "1. Attaquer\n";
            echo "2. Se défendre\n\n";

            $choixCombat = readline();

            switch ($choixCombat) {
                case 1:
                    $sqlArme = $this->db->prepare("SELECT * FROM inventaire_personnage ip JOIN objet o ON ip.objet_id = o.id WHERE ip.personnage_id = :personnageId");
                    $sqlArme->execute([
                        "personnageId" => $personnageId
                    ]);
                    $arme = $sqlArme->fetch();
                    $degatsJoueur = max(0, $personnage['points_d_attaque'] + $arme['degats']);
                
                    $sql = $this->db->prepare("UPDATE monstres SET points_de_vie = points_de_vie - :degats WHERE id = :monstreId");
                    $sql->execute([
                        "degats" => $degatsJoueur,
                        "monstreId" => $monstreId,
                    ]);
                
                    echo "\033[2J\033[;H";
                
                    echo "Vous avez infligé $degatsJoueur points de dégâts au monstre!\n";
                    break;
                case 2:
                    $degatsMonstre = max(0, $monstre['points_d_attaque'] - $personnage['points_de_defense']);
                    $this->infligerDegatsPersonnage($personnageId, $degatsMonstre);

                    echo "Vous avez subi $degatsMonstre points de dégâts!\n";
                    break;
                default:
                    echo "Choix invalide. Veuillez choisir 1 ou 2.\n";
            }

            $personnage = $this->getPersonnage($personnageId);
            $monstre = $this->getMonstre($monstreId);

            if ($monstre['points_de_vie'] > 0) {
                $degatsMonstre = max(0, $monstre['points_d_attaque']);

                $this->infligerDegatsPersonnage($personnageId, $degatsMonstre);

                $personnage = $this->getPersonnage($personnageId);
                echo "Le monstre vous a infligé $degatsMonstre points de dégâts!\n";
            } else {
                $sql = $this->db->prepare("UPDATE personnages SET experience = experience + :experience WHERE id = :personnageId");
                $sql->execute([
                    "experience" => $monstre['experience'],
                    "personnageId" => $personnageId,
                ]);

                echo "Vous avez vaincu le monstre!\n";

                $personnage = $this->getPersonnage($personnageId);
                echo "Vous avez gagné " . $monstre['experience'] . " points d'expérience!\n";
            }

            if ($personnage['points_de_vie'] < 0) {
                echo "Vous avez été vaincu par le monstre!\n";
            }
            
            sleep(3);
        }
    }

}

$dao = new DAO($db);

echo "Que voulez-vous faire ?\n";
echo "1. Jouer\n";
echo "2. Nouvelle partie\n";
echo "3. Quitter\n\n";

$choix = readline();

echo "\033[2J\033[;H";

switch ($choix) {
    case 1:
        $dao->choixPersonnage();
        break;
    case 2:
        echo "Vous chargez une partie\n";
        break;
    case 3:
        echo "Fermeture du jeu\n";
        exit;
    default:
        echo "Choix invalide ";
        sleep(1);
        break;
}

?>