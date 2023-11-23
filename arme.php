<?php 

class Arme
{
    public $nom;
    public $degats;
    public $niveauRequie;

    public function __construct($nom, $degats, $levelRequie) {
        $this->nom = $nom;
        $this->degats = $degats;
        $this->levelRequie = $levelRequie;
    }


}

?>