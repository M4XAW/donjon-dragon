<?php

class Salle 
{
    protected $id;
    protected $nom;
    protected $description;

    public function __construct($id, $nom ,$description) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
    }

    public function getId() {
        return $this->id;
    }
    
    public function getNom(){
        return $this->nom;
    }

    public function getDescription(){
        return $this->description;
    }
}

class SalleCombat extends Salle
{
    private $monstre;

    public function __construct($id, $nom ,$description, $monstre) {
        parent::__construct($id, $nom ,$description);
        $this->monstre = $monstre;
    }

    public function getMonstre() {
        return $this->monstre;
    }
}

class SallePiege extends Salle
{
    private $degats;

    public function __construct($id, $nom ,$description, $degats) {
        parent::__construct($id, $nom ,$description);
        $this->degats = $degats;
    }

    public function getDegats() {
        return $this->degats;
    }
}

class SalleMarchand extends Salle 
{
    private $objet;

    public function __construct($id, $nom ,$description, $objet) {
        parent::__construct($id, $nom ,$description);
        $this->objet = $objet;
    }

    public function getObjet() {
        return $this->objet;
    }
}

class SalleEnigme extends Salle 
{
    private $enigme;

    public function __construct($id, $nom ,$description, $enigme) {
        parent::__construct($id, $nom ,$description);
        $this->enigme = $enigme;
    }

    public function getEnigme() {
        return $this->enigme;
    }
}