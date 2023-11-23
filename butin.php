<?php
class Butin {
    private $id;
    private $nom;
    private $type;
    private $description;
    private $bonus;
    private $malus;

    public function __construct($id, $nom ,$type, $description, $bonus, $malus) {
        $this->id = $id;
        $this->nom = $nom;
        $this->type = $type;
        $this->description = $description;
        $this->bonus = $bonus;
        $this->malus = $malus;
    }

    public function getId(){
        return $this->id;
    }
    public function getNom(){
        return $this->nom;
    }
    public function getType(){
        return $this->type;
    }
    public function getDescription(){
        return $this->description;
    }
    public function getBonus(){
        return $this->bonus;
    }
    public function getMalus(){
        return $this->malus;
    }
}