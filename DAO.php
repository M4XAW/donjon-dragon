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

    public function infligerDegatsMonstre($monstreId, $degats)
    {
        $sql = $this->db->prepare("UPDATE monstres SET points_de_vie = points_de_vie - :degats WHERE id = :monstreId");
        $sql->execute([
            "degats" => $degats,
            "monstreId" => $monstreId,
        ]);
    }

    public function verificationPlaceInventaire($personnageId)
    {
        $sql = $this->db->prepare("SELECT COUNT(*) as nb FROM inventaire_personnage WHERE personnage_id = :personnageId");
        $sql->execute([
            "personnageId" => $personnageId
        ]);
        $result = $sql->fetch();
        
        if ($result['nb'] >= 10) {
            echo "Votre inventaire est plein !\n";
            return false;
        } else {
            echo "Vous avez récupéré un objet !\n";
            return true;
        }
    }

    public function choixPersonnage()
    {
        while (true) {
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

            if ($choix == 1 || $choix == 2 || $choix == 3) {
                return $choix;
            } else {
                echo "Personnage introuvable ";
                sleep(1);
                continue;
            }

            break;
        }
    }

    public function gestionSalles($choix)
    {
        while(true) {
            $personnage = $this->getPersonnage($choix);
            
            if ($personnage['points_de_vie'] <= 0) {
                echo "Vous êtes mort !\n";
                return;
            }
            
            // $salleId = $this->choisirSalleAleatoire();
            $salleId = 1;
            $this->mettreAJourSalleActuelle($personnage['id'], $salleId);
            $this->afficherInfosSalle($salleId, $personnage['id']);

            sleep(2);

            echo "\033[2J\033[;H";

            echo "Voulez-vous continuer ? (oui/non) : ";
            $continuer = strtolower(readline());

            //tant que le joueur ne choisi pas oui ou non, il repose la question

            if ($continuer == "non") {
                echo "Vous avez quitté le jeu.\n";
                exit;
            } else {
                continue;
            }
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
                $this->afficherInfosPersonnage($personnageId);
                $this->afficherOptionsMarchand($personnageId, $result["id"]);
                break;
            case "Piège":
                $this->afficherInfosPersonnage($personnageId);
                $this->appliquerEffetPiege($personnageId);
                break;
                case "Questions":
                $this->afficherInfosPersonnage($personnageId);
                $this->afficherQuestion($result["id"],$personnageId);
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
        echo "Vous entrez dans la salle d'un marchand !\n\n";

        $objetDemande = $this->objetAleatoire();
        $objetEchange = $this->objetAleatoire();

        while ($objetDemande['id'] == $objetEchange['id']) {
            $objetEchange = $this->objetAleatoire();
        }
        
        echo "Le marchand vous propose un(e) " . $objetDemande['nom'] . " contre un(e) " . $objetEchange['nom'] . ".\n\n";

        while (true) {
            echo "Que voulez-vous faire ?\n";
            echo "1. Accepter l'échange\n";
            echo "2. Refuser l'échange\n\n";

            $choix = readline();

            echo "\033[2J\033[;H";

            if ($choix == 1) {
                if ($this->personnagePossedeObjet($personnageId, $objetEchange['id'])) {
                    $this->ajouterObjetDansInventaire($personnageId, $objetDemande['id']);
                    $this->retirerObjetDeInventaire($personnageId, $objetEchange['id']);
                    
                    echo "Vous avez échangé votre " . $objetEchange['nom'] . " contre un " . $objetDemande['nom'] . ". ";
                } else {
                    echo "Vous n'avez pas l'objet " . $objetEchange['nom'] . " dans votre inventaire.\n";
                }
                break;
            } else if ($choix == 2) {
                echo "Vous avez refusé l'échange ";
                break;
            } else {
                echo "Choix invalide. Veuillez choisir 1 ou 2 ";
                continue;
            }
            
            sleep(2);
        }
    }

    private function personnagePossedeObjet($personnageId, $objetId)
    {
        $sql = $this->db->prepare("SELECT COUNT(*) as nb FROM inventaire_personnage WHERE personnage_id = :personnageId AND objet_id = :objetId");
        $sql->execute([
            "personnageId" => $personnageId,
            "objetId" => $objetId,
        ]);
        $result = $sql->fetch();

        return $result['nb'] > 0;
    }

    private function afficherQuestion($salleId, $personnageId)
    {
        $sql = $this->db->prepare("SELECT q.id, q.question, q.reponse FROM enigmes q JOIN salles s ON q.id = s.enigme_id WHERE s.id = :salleId ORDER BY RAND()");
        $sql->execute([
            "salleId" => $salleId,
        ]);
        $enigme = $sql->fetch();

        if ($enigme === false) {
            echo "Erreur : Enigme non trouvée pour cette salle.\n";
            return;
        }

        echo "Vous entrez dans une salle de questions !\n";
        echo "Question : {$enigme['question']}\n";

        $reponseUtilisateur = strtolower(readline("Votre réponse : "));

        if ($reponseUtilisateur === strtolower($enigme['reponse'])) {
            echo "Félicitations, votre réponse est correcte!\n";

            $objet = $this->objetAleatoire();
            $nomObjet = $objet['nom'];
            echo "Vous avez obtenu un nouvel objet : $nomObjet!\n";
            if ($objet['type'] === 'Arme') {
                $armeExistante = $this->getArmeDansInventaire($personnageId);
                
                if ($armeExistante) {
                    echo "Vous avez déjà une arme dans votre inventaire : {$armeExistante['nom']}.\n";
                    $choixEchange = strtolower(readline("Voulez-vous échanger votre arme actuelle avec la nouvelle ? (oui/non) : "));

                    if ($choixEchange === 'oui') {
                        $this->echangeArme($personnageId, $armeExistante['id'], $objet['id']);
                    }
                } else {
                    $this->ajouterObjetDansInventaire($personnageId, $objet['id']);
                    echo "Vous avez obtenu une nouvelle arme : {$objet['nom']}!\n";
                }
            } else {
                $this->ajouterObjetDansInventaire($personnageId, $objet['id']);
                echo "Vous avez obtenu un nouvel objet : {$objet['nom']}!\n";
            }
        } else {
            echo "Désolé, votre réponse est incorrecte. Vous ne gagnez pas d'objet.\n";
        }
    }

    private function objetAleatoire() {
        $sql = $this->db->prepare("SELECT * FROM objet ORDER BY RAND() LIMIT 1");
        $sql->execute();
        return $sql->fetch();
    }

    private function getArmeDansInventaire($personnageId) {
        $sql = $this->db->prepare("SELECT * FROM inventaire_personnage ip JOIN objet o ON ip.objet_id = o.id WHERE ip.personnage_id = :personnageId AND o.type = 'Arme'");
        $sql->execute([
            "personnageId" => $personnageId
        ]);
        return $sql->fetch();
    }
    
    private function echangeArme($personnageId, $ancienneArmeId, $nouvelleArmeId) {
        $this->retirerObjetDeInventaire($personnageId, $ancienneArmeId);
        $this->ajouterObjetDansInventaire($personnageId, $nouvelleArmeId);
    }
    
    private function ajouterObjetDansInventaire($personnageId, $objetId) {
        $sql = $this->db->prepare("INSERT INTO inventaire_personnage (personnage_id, objet_id) VALUES (:personnageId, :objetId)");
        $sql->execute([
            "personnageId" => $personnageId,
            "objetId" => $objetId
        ]);
    }
    private function retirerObjetDeInventaire($personnageId, $objetId) {
        $sqlSelect = $this->db->prepare("SELECT * FROM objet WHERE id = :objetId");
        $sqlSelect->execute([
            "objetId" => $objetId
        ]);
        $objetInfo = $sqlSelect->fetch();
    
        // Supprimer l'objet de l'inventaire du personnage
        $sqlDelete = $this->db->prepare("DELETE FROM inventaire_personnage WHERE personnage_id = :personnageId AND objet_id = :objetId LIMIT 1");
        $sqlDelete->execute([
            "personnageId" => $personnageId,
            "objetId" => $objetId
        ]);
    }
    
    
    private function appliquerEffetPiege($personnageId)
    {
        echo "Vous entrez dans une salle de piège ! ";

        sleep(2);
        echo "\033[2J\033[;H";

        $positionPiege = rand(1, 2); 

        while(true) {
            echo "Que voulez-vous faire ?\n";
            echo "1. Aller à gauche\n";
            echo "2. Aller à droite\n\n";
    
            $choix = readline();

            if ($choix == $positionPiege) {
                $degatsPiege = rand(5, 15);
                $this->infligerDegatsPersonnage($personnageId, $degatsPiege);
                echo "\nVous êtes tombé dans un piège ! ";
                sleep(2);
                echo "Vous avez perdu " . $degatsPiege . " points de vie !\n";
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
            $personnage = $this->getPersonnage($personnageId);
            $monstre = $this->getMonstre($monstreId);

            if ($personnage['points_de_vie'] <= 0) {
                echo "\033[2J\033[;H";
                echo "Vous avez été vaincu par le monstre !\n";
                sleep(2);
                exit;
            }    

            if ($monstre['points_de_vie'] > 0) {
                //echo "\033[2J\033[;H";
            
                $this->afficherInfosPersonnage($personnageId);
    
                echo "Options de combat:\n";
                echo "1. Attaquer\n";
                echo "2. Se défendre\n";
                echo "3. Se soigner\n\n";
    
                $choixCombat = readline();
    
                echo "\033[2J\033[;H";

                switch ($choixCombat) {
                    case 1:
                        $sqlArme = $this->db->prepare("SELECT * FROM inventaire_personnage ip JOIN objet o ON ip.objet_id = o.id WHERE ip.personnage_id = :personnageId");
                        $sqlArme->execute([
                            "personnageId" => $personnageId
                        ]);
                        $arme = $sqlArme->fetch();
                        $degatsJoueur = max(0, $personnage['points_d_attaque'] + $arme['degats']);

                        $this->infligerDegatsMonstre($monstreId, $degatsJoueur);

                        echo "\033[2J\033[;H";
                
                        echo "Vous avez infligé $degatsJoueur points de dégâts au monstre ! ";
                        sleep(2);
            
                        $degatsMonstre = max(0, $monstre['points_d_attaque']);
        
                        $this->infligerDegatsPersonnage($personnageId, $degatsMonstre);
                    
                        echo "\033[2J\033[;H";
                        echo "Le monstre vous a infligé $degatsMonstre points de dégâts ! ";
                        sleep(2);
                        
                        break;
                    case 2:
                        $degatsMonstre = max(0, $monstre['points_d_attaque'] - $personnage['points_de_defense']);    
                        $this->infligerDegatsPersonnage($personnageId, $degatsMonstre);
                        echo "\nVous avez paré l'attaque du monstre ! Vous avez perdu $degatsMonstre points de vie.\n";
                        break;
                        case 3:
                            echo "Cas 3 : Utiliser une potion de soin\n";
                            
                            $potions = $this->getPotionsDansInventaire($personnageId);
                            
                            if ($potions) {
                                $potionChoisie = reset($potions); 
                        
                                $objetId = $potionChoisie['objet_id'];
                        
                                $this->soignerPersonnage($personnageId, $potionChoisie,['taux_soin']);
                                $this->retirerObjetDeInventaire($personnageId, $objetId);
                                sleep(20);
                            } else {
                                echo "Vous n'avez pas de potions de soin dans votre inventaire.\n";
                            }
                            break;
                                             
                default:
                    echo "Choix invalide. Veuillez choisir 1 ou 2.\n";
                }
            } else {
                $sql = $this->db->prepare("UPDATE personnages SET experience = experience + :experience WHERE id = :personnageId");
                $sql->execute([
                    "experience" => $monstre['experience'],
                    "personnageId" => $personnageId,
                ]);

                echo "\033[2J\033[;H";

                echo "Vous avez vaincu le monstre!\n";
                $objet = $this->objetAleatoire();
                $nomObjet = $objet['nom'];
                echo "Vous avez obtenu un nouvel objet : $nomObjet!\n";

                sleep(3);

                $personnage = $this->getPersonnage($personnageId);
                echo "Vous avez gagné " . $monstre['experience'] . " points d'expérience! ";
                sleep(2);
            }            
        }
    }
    private function getPotionsDansInventaire($personnageId)
    {
        $sql = $this->db->prepare("SELECT * FROM inventaire_personnage ip JOIN objet o ON ip.objet_id = o.id WHERE ip.personnage_id = :personnageId AND o.type = 'Potion'");
        $sql->execute([
            "personnageId" => $personnageId
        ]);
        return $sql->fetchAll();
    }
    private function soignerPersonnage($personnageId, $objetId, $tauxSoin)
{
    $sqlUpdate = $this->db->prepare("UPDATE personnages SET points_de_vie = points_de_vie + 15 WHERE id = :personnageId");
    $sqlUpdate->execute([
        "personnageId" => $personnageId
    ]);

    $this->retirerObjetDeInventaire($personnageId, $objetId);

    echo "Vous avez utilisé la potion de soin et vous avez été soigné de 15 points de vie!\n";
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
        $choix = $dao->choixPersonnage();
        $dao->gestionSalles($choix);
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