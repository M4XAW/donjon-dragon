<?php 

class Monstre
{
    public $nom;
    public $vie;
    public $degats;
    public $experience;

    public function __construct($nom, $vie, $degats, $experience) {
        $this->nom = $nom;
        $this->vie = $vie;
        $this->degats = $degats;
        $this->experience = $experience;
    }

    public function getNom(){
        return $this->nom;
    }

    public function getVie(){
        return $this->vie;
    }

    public function getDegats(){
        return $this->degats;
    }

    public function getExperience(){
        return $this->experience;
    }

    public function setNom($nom){
        $this->nom = $nom;
    }

    public function setVie($vie){
        $this->vie = $vie;
    }

    public function setDegats($degats){
        $this->degats = $degats;
    }

    public function setExperience($experience){
        $this->experience = $experience;
    }
}

?>