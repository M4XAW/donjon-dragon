<?php

class DAO
{
    public $db; // Pour stocker la connexion à la base de données

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function infligerDegatsPersonnage($personnageId, $degats) // Fonction pour infliger des dégâts au personnage
    {
        // On récupère les points de vie du personnage
        $sql = $this->db->prepare("UPDATE personnages SET points_de_vie = points_de_vie - :degats WHERE id = :personnageId");
        $sql->execute([ // On exécute la requête pour mettre à jour les points de vie du personnage
            "degats" => $degats,
            "personnageId" => $personnageId,
        ]);
    }

    public function infligerDegatsMonstre($monstreId, $degats) // Fonction pour infliger des dégâts au monstre
    {
        $sql = $this->db->prepare("UPDATE monstres SET points_de_vie = points_de_vie - :degats WHERE id = :monstreId");
        $sql->execute([ // On exécute la requête pour mettre à jour les points de vie du monstre
            "degats" => $degats,
            "monstreId" => $monstreId,
        ]);
    }

    public function verificationPlaceInventaire($personnageId) // Fonction pour vérifier si le personnage a de la place dans son inventaire
    {
        $sql = $this->db->prepare("SELECT COUNT(*) as nb FROM inventaire_personnage WHERE personnage_id = :personnageId"); // On compte le nombre d'objets dans l'inventaire du personnage
        $sql->execute([
            "personnageId" => $personnageId // On récupère l'id du personnage
        ]);
        $result = $sql->fetch();
        
        if ($result['nb'] >= 10) { // Si le nombre d'objets dans l'inventaire est supérieur ou égal à 10
            echo "Votre inventaire est plein !\n";
            return false; // On retourne false
        } else {
            echo "Vous avez récupéré un objet !\n"; 
            return true;
        }
    }

    public function choixPersonnage() // Fonction pour choisir un personnage
    {
        while (true) { // Tant que le choix n'est pas valide, on repose la question
            echo "\033[2J\033[;H"; // On efface le terminal

            echo "Voici la liste des personnages\n\n";
            $sql = $this->db->prepare("SELECT * FROM personnages"); // On récupère tous les personnages
            $sql->execute();
            $result = $sql->fetchAll();

            foreach ($result as $personnage) { // On affiche tous les personnages
                echo $personnage["id"] . ". " . $personnage["nom"] . "\n";
            }

            if (count($result) == 0) { // Si il ne renvoie aucun résultat
                echo "Aucun personnage\n";
            }

            echo "\n";

            $choix = readline();

            if ($choix == 1 || $choix == 2 || $choix == 3) { // Si le choix est valide
                return $choix;
            } else {
                echo "Personnage introuvable ";
                sleep(1);
                continue; // On repose la question
            }

            break;
        }
    }

    public function gestionSalles($choix) // Fonction pour gérer les salles
    {
        while(true) { // BOucle infinie
            $personnage = $this->getPersonnage($choix); // On récupère le personnage choisi
            
            if ($personnage['points_de_vie'] <= 0) { // Si le personnage est mort
                echo "Vous êtes mort !\n";
                return;
            }
            
            $salleId = $this->choisirSalleAleatoire(); // On choisit une salle aléatoire
            $this->mettreAJourSalleActuelle($personnage['id'], $salleId); // On met à jour la salle actuelle du personnage
            $this->afficherInfosSalle($salleId, $personnage['id']); // On affiche les informations de la salle

            sleep(2);

            echo "\033[2J\033[;H";

            echo "Voulez-vous continuer ? (oui/non) : "; // On demande au joueur s'il veut continuer
            $continuer = strtolower(readline()); // On récupère la réponse du joueur

            //tant que le joueur ne choisi pas oui ou non, il repose la question

            if ($continuer == "non") { // Si le joueur ne veut pas continuer
                echo "Vous avez quitté le jeu.\n";
                exit; // On quitte le jeu
            } else {
                continue;
            }
        }
    }

    public function getPersonnage($id) // Fonction pour récupérer un personnage
    {
        $sql = $this->db->prepare("SELECT * FROM personnages WHERE id = :id"); // On récupère le personnage avec l'id
        $sql->execute([
            "id" => $id
        ]);
        $result = $sql->fetch();

        return $result;
    }
    private function getMonstre($monstreId) // Fonction pour récupérer un monstre
    {
        $sql = $this->db->prepare("SELECT * FROM monstres WHERE id = :monstreId"); // On récupère le monstre avec l'id
        $sql->execute([ // On exécute la requête
            "monstreId" => $monstreId,
        ]);
        $monstre = $sql->fetch();

        return $monstre; // On retourne le monstre
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

    public function afficherInfosPersonnage($id) // Fonction pour afficher les informations du personnage
    {
        echo "\033[2J\033[;H";

        $sql = $this->db->prepare("SELECT * FROM personnages WHERE id = :id"); // On récupère le personnage avec l'id
        $sql->execute([
            "id" => $id
        ]);

        $result = $sql->fetch();

        if ($result === false) { // Si le résultat est faux
            echo "Personnage introuvable.\n";
            return;
        }

        echo "👤 " . $result["nom"] . " | ❤️  " . $result["points_de_vie"] . " | 🗡️  " . $result["points_d_attaque"] . " | 🛡️  " . $result["points_de_defense"] . " | 🔥 " . $result["experience"] . " | 🎖️  " . $result["niveau"] . "\n\n";

        echo "Inventaire : ";
        $sql = $this->db->prepare("SELECT ip.id, o.nom FROM inventaire_personnage ip JOIN objet o ON ip.objet_id = o.id WHERE ip.personnage_id = :id"); // On récupère les objets de l'inventaire du personnage
        $sql->execute([
            "id" => $id
        ]);
        $result = $sql->fetchAll();

        foreach ($result as $item) { // On affiche les objets de l'inventaire du personnage
            echo $item["nom"] . " | ";
        }

        if (count($result) == 0) {
            echo "Aucun item\n";
        }

        echo "\n\n";
    }

    private function choisirSalleAleatoire() // Fonction pour choisir une salle aléatoire
    {
        $sql = $this->db->prepare("SELECT id FROM salles ORDER BY RAND() LIMIT 1"); // On récupère une salle aléatoire
        $sql->execute();
        $result = $sql->fetch();

        return $result['id'];
    }

    private function mettreAJourSalleActuelle($personnageId, $salleId) // Fonction pour mettre à jour la salle actuelle du personnage
    {
        $sql = $this->db->prepare("UPDATE personnages SET salle_actuelle = :salleId WHERE id = :personnageId"); // On met à jour la salle actuelle du personnage
        $sql->execute([
            "salleId" => $salleId,
            "personnageId" => $personnageId,
        ]);
    }
    
    private function afficherInfosSalle($salleId, $personnageId) // Fonction pour afficher les informations de la salle
    {
        $sql = $this->db->prepare("SELECT * FROM salles WHERE id = :salleId"); // On récupère la salle avec l'id
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

        switch ($result["type"]) { // On affiche les informations en fonction du type de salle
            case "Monstre":
                $monstreId = $this->getMonstreId($salleId); // On récupère l'id du monstre
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

    private function getMonstreId($salleId) // Fonction pour récupérer l'id du monstre
    {
        $sql = $this->db->prepare("SELECT monstre_id FROM salles WHERE id = :salleId"); // On récupère l'id du monstre
        $sql->execute([
            "salleId" => $salleId,
        ]);
        $result = $sql->fetch();

        return $result ? $result['monstre_id'] : null;
    }

    private function afficherOptionsMarchand($personnageId, $salleId) // Fonction pour afficher les options du marchand
    {
        echo "Vous entrez dans la salle d'un marchand !\n\n";

        $objetDemande = $this->objetAleatoire(); // On récupère un objet aléatoire
        $objetEchange = $this->objetAleatoire(); 

        while ($objetDemande['id'] == $objetEchange['id']) { // Tant que l'objet demandé est le même que l'objet échangé
            $objetEchange = $this->objetAleatoire();
        }
        
        echo "Le marchand vous propose un(e) " . $objetDemande['nom'] . " contre un(e) " . $objetEchange['nom'] . ".\n\n";

        while (true) {
            echo "Que voulez-vous faire ?\n";
            echo "1. Accepter l'échange\n";
            echo "2. Refuser l'échange\n\n";

            $choix = readline();

            echo "\033[2J\033[;H";

            if ($choix == 1) { // Si le joueur accepte l'échange
                if ($this->personnagePossedeObjet($personnageId, $objetEchange['id'])) { // Si le personnage possède l'objet
                    $this->ajouterObjetDansInventaire($personnageId, $objetDemande['id']);
                    $this->retirerObjetDeInventaire($personnageId, $objetEchange['id']);
                    
                    echo "Vous avez échangé votre " . $objetEchange['nom'] . " contre un " . $objetDemande['nom'] . ". "; // On affiche un message
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

    private function personnagePossedeObjet($personnageId, $objetId) // Fonction pour vérifier si le personnage possède l'objet
    {
        $sql = $this->db->prepare("SELECT COUNT(*) as nb FROM inventaire_personnage WHERE personnage_id = :personnageId AND objet_id = :objetId"); // On compte le nombre d'objets dans l'inventaire du personnage
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
                        sleep(2);
                        break;
                    case 3:                        
                        $potions = $this->getPotionsDansInventaire($personnageId);
                        
                        if ($potions) {
                            $potionChoisie = reset($potions); 
                    
                            $objetId = $potionChoisie['objet_id'];
                    
                            $this->soignerPersonnage($personnageId, $potionChoisie,['taux_soin']);
                            $this->retirerObjetDeInventaire($personnageId, $objetId);
                            sleep(20);
                        } else {
                            echo "Vous n'avez pas de potions de soin dans votre inventaire ";
                        }

                        sleep(2);
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