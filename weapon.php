<?php 

class Weapon 
{
    public $nom;
    public $degats;
    public $levelRequie;

    public function __construct($nom, $degats, $levelRequie) {
        $this->nom = $nom;
        $this->degats = $degats;
        $this->levelRequie = $levelRequie;
    }

    
}
?>