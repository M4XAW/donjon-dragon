<?php

class Salle {
    protected $id;
    protected $nom;
    protected $type;
    protected $description;

    public function __construct($id, $nom, $type, $description) {
        $this->id = $id;
        $this->nom = $nom;
        $this->type = $type;
        $this->description = $description;
    }

    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getType() {
        return $this->type;
    }

    public function getDescription() {
        return $this->description;
    }
}

class SallePiege extends Salle {
    private $degats;

    public function __construct($id, $nom, $type, $description, $degats) {
        parent::__construct($id, $nom, $type, $description);
        $this->degats = $degats;
    }

    public function getDegats() {
        return $this->degats;
    }
}

class SalleMarchand extends Salle {
    private $objet;

    public function __construct($id, $nom, $type, $description, $objet) {
        parent::__construct($id, $nom, $type, $description);
        $this->objet = $objet;
    }

    public function getObjet() {
        return $this->objet;
    }
}
