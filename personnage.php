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

    public function setPointDeVie($pointDeVie) {
        $this->pointDeVie = $pointDeVie;
    }
}

?>