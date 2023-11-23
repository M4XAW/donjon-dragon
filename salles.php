<?php

class Salle 
{
    protected $id;
    protected $description;

    public function __construct($id ,$description) {
        $this->id = $id;
        $this->description = $description;
    }

    public function getId() {
        return $this->id;
    }

    public function getDescription(){
        return $this->description;
    }
}

class SalleCombat extends Salle
{
    private $monstre;

    public function __construct($id ,$description, $monstre) {
        parent::__construct($id ,$description);
        $this->monstre = $monstre;
    }

    public function getMonstre() {
        return $this->monstre;
    }
}

class SallePiege extends Salle
{
    private $degats;

    public function __construct($id ,$description, $degats) {
        parent::__construct($id ,$description);
        $this->degats = $degats;
    }

    public function getDegats() {
        return $this->degats;
    }

    public function setDegats($degats) {
        $this->degats = $degats;
    }

    public function activerPiege(Personnage $personnage) {
        $personnage->setPointDeVie($personnage->getPointDeVie() - $this->degats);
        echo "Vous avez perdu " . $this->degats . " points de vie.\n";
    }
}

class SalleMarchand extends Salle 
{
    private $objet;

    public function __construct($id ,$description, $objet) {
        parent::__construct($id ,$description);
        $this->objet = $objet;
    }

    public function getObjet() {
        return $this->objet;
    }

    // public function echangerObjet(Personnage $personnage) {
    //     $dao->ajouterObjet($personnage->recupererId(), $this->objet);
    //     $dao->retirerObjet($personnage->recupererId(), 2);
    //     echo "Vous avez économisé " . $this->objet . " contre un objet.\n";
    // }
}

class SalleEnigme extends Salle 
{
    private $enigme;

    public function __construct($id ,$description) {
        parent::__construct($id ,$description);
        $this->enigme = "Qu'est ce qui est jaune et qui attend ?";
    }

    public function getEnigme() {
        return $this->enigme;
    }

    public function reponseEnigme($reponse) {
        if ($reponse === "Jonathan") {
            echo "Bravo !\n";
        } else {
            echo "Mauvaise réponse !\n";
        }
    }
}

$dao = new DAO($db);
