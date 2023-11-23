<?php 

include("config.php");
include("DAO.php");

class Personnage 
{
    public $nom;
    public $pointDeVie;
    public $pointAttaque;
    public $pointDefense;
    public $experience;
    public $niveau;

    public function __construct($nom, $pointDeVie, $pointAttaque, $pointDefense, $experience, $niveau) {
        $this->nom = $nom;
        $this->pointDeVie = $pointDeVie;
        $this->pointAttaque = $pointAttaque;
        $this->pointDefense = $pointDefense;
        $this->experience = $experience;
        $this->niveau = $niveau;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPointDeVie() {
        return $this->pointDeVie;
    }

    public function getPointAttaque() {
        return $this->pointAttaque;
    }

    public function getPointDefense() {
        return $this->pointDefense;
    }

    public function getExperience() {
        return $this->experience;
    }

    public function getNiveau() {
        return $this->niveau;
    }
}

$dao = new DAO($db);

echo "Que voulez-vous faire ?\n";
echo "1. Jouer\n";
echo "2. Nouvelle partie\n";
echo "3. Quitter\n\n";

$choix = readline();

echo "\033[2J\033[;H";

switch($choix) {
    case 1:
        $dao->choixPersonnage();

        // $salle = new SallePiege(1, "Salle 1", "Vous êtes dans la salle 1", 10);
        // $salle2 = new SalleMarchand(2, "Salle 2", "Vous êtes dans la salle 2", "Potion");

        break;
    case 2:
        echo "Vous chargez une partie\n";
        break;
    case 3:
        echo "Vous quittez le jeu\n";
        break;
    default:
        echo "Choix invalide\n";
        break;
}

?>