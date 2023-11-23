<?php 

include("config.php");
include("DAO.php");

class Personnage 
{
    public $id;
    public $nom;
    public $pointDeVie;
    public $pointAttaque;
    public $pointDefense;
    public $experience;
    public $niveau;

    public function __construct($id, $nom, $pointDeVie, $pointAttaque, $pointDefense, $experience, $niveau) {
        $this->id = $id;
        $this->nom = $nom;
        $this->pointDeVie = $pointDeVie;
        $this->pointAttaque = $pointAttaque;
        $this->pointDefense = $pointDefense;
        $this->experience = $experience;
        $this->niveau = $niveau;
    }

    public function getId() {
        return $this->id;
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
echo "2. Charger une partie\n";
echo "3. Quitter\n\n";

$choix = readline();

echo "\033[2J\033[;H";

switch($choix) {
    case 1:
        $personnage = $dao->choixPersonnage();
        if ($personnage !== null) {
            $personnage->afficherInventaire($dao);
        } else {
            echo "Personnage non défini.\n";
        }
        break;
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