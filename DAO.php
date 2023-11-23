<?php 

class DAO 
{
    public $db;

    public function __construct($db) {
        $this->db = $db;
    }



    public function choixPersonnage() {
        while (true) {
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
                return;
            }

            echo "\n";

            $choix = readline();

            $sql = $this->db->prepare("SELECT * FROM personnages WHERE id = :id");
            $sql->execute([
                "id" => $choix
            ]);

            $selectedPersonnage = $sql->fetch();

            if ($selectedPersonnage === false) {
                echo "\033[2J\033[;H";
                echo "Personnage introuvable ";
                sleep(1);
                continue;
            }

            break;
        }

        $this->afficherInfosPersonnage($selectedPersonnage["id"]);
    }

    public function afficherInventaire($id) {
        echo "Inventaire : ";
        $sql = $this->db->prepare("SELECT ip.id, o.nom FROM inventaire_personnage ip JOIN objet o ON ip.objet_id = o.id WHERE ip.personnage_id = :id");
        $sql->execute([
            "id" => $id
        ]);
        $result = $sql->fetchAll();

        foreach($result as $item) {
            echo $item["nom"] . " | ";
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
    
        echo "👤 " . $result["nom"] . " | ❤️  " . $result["points_de_vie"] . " | 🗡️  " . $result["points_d_attaque"] . " | 🛡️  " . $result["points_de_defense"] . " | 🔥 " . $result["experience"] . " | 🎖️  " . $result["niveau"] . "\n\n";
        $this->afficherInventaire($id);
        echo "\n\n";
    }

    public function ajouterObjet($id, $objet) {
        $sql = $this->db->prepare("INSERT INTO inventaire_personnage (personnage_id, objet_id) VALUES (:personnage_id, :objet_id)");
        $sql->execute([
            "personnage_id" => $id,
            "objet_id" => $objet
        ]);
    }

    public function retirerObjet($id, $objet) {
        $sql = $this->db->prepare("DELETE FROM inventaire_personnage WHERE personnage_id = :personnage_id AND objet_id = :objet_id");
        $sql->execute([
            "personnage_id" => $id,
            "objet_id" => $objet
        ]);

        echo "Vous avez déposé l'objet.\n";
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

    public function randomSalle() {
        $sql = $this->db->prepare("SELECT * FROM salles");
        $sql->execute();
        $result = $sql->fetchAll();
        $random = rand(0, count($result) - 1);

        if ($result === false) {
            echo "Salle introuvable.\n";
            return;
        }

        echo $result[$random]["description"] . "\n";

        return $result[$random];
    }
}

$dao = new DAO($db);

while(true) {

    echo "Que voulez-vous faire ?\n";
    echo "1. Jouer\n";
    echo "2. Nouvelle partie\n";
    echo "3. Quitter\n\n";

    $choix = readline();

    echo "\033[2J\033[;H";

    switch($choix) {
        case 1:
            $dao->choixPersonnage();
            $dao->randomSalle();

            // $dao->ajouterObjet(1, 2); // Ajoute une potion au personnage 1
            // $dao->retirerObjet(1, 2); // Retire une potion au personnage 1

            // $salle = new SallePiege(1, "Salle 1", "Vous êtes dans la salle 1", 10);
            // $salle2 = new SalleMarchand(2, "Salle 2", "Vous êtes dans la salle 2", "Potion");

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
}


?>