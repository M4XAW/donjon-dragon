<?php
class salles 
{
    private $id;
    private $nom;
    private $type;
    private $description;

    public function __construct($id, $nom, $type ,$description) {
        $this->id = $id;
        $this->nom = $nom;
        $this->type = $type;
        $this->description = $description;
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
}