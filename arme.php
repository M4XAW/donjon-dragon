<?php 

class Arme
{
    public $nom;
    public $degats;
    public $niveauRequie;

    public function __construct($nom, $degats, $niveauRequie) {
        $this->nom = $nom;
        $this->degats = $degats;
        $this->niveauRequie = $niveauRequie;
    }


}
?>