<?php

class Monstre{
    private $id;
    private $nom;
    private $pointDeVie;
    private $pointAttaque;
    private $experience;
    private $niveau;
    private $salle_id;

    public function __construct($id, $nom, $pointDeVie, $pointAttaque, $experience, $niveau, $salle_id) {
        $this->id = $id;
        $this->nom = $nom;
        $this->pointDeVie = $pointDeVie;
        $this->pointAttaque = $pointAttaque;
        $this->experience = $experience;
        $this->niveau = $niveau;
        $this->salle_id = $salle_id;
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

    public function getExperience() {
        return $this->experience;
    }

    public function getNiveau() {
        return $this->niveau;
    }

    public function getSalle_id() {
        return $this->salle_id;
    }
}

?>