<?php
class Monstre{
    private $id;
    private $name;
    private $health;
    private $attackPoints;
    private $defensePoints;
    private $level;
    private $salle_id;

    public function __construct($id,$name, $health, $attackPoints, $defensePoints, $salle_id) {
        $this->id =$id;
        $this->name = $name;
        $this->health = $health;
        $this->attackPoints = $attackPoints;
        $this->defensePoints = $defensePoints;
        $this->level = 1;
        $this->salle_id = $salle_id;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getHealth() {
        return $this->health;
    }

    public function getAttackPoints() {
        return $this->attackPoints;
    }

    public function getDefensePoints() {
        return $this->defensePoints;
    }

    public function getLevel() {
        return $this->level;
    }

    public function getSalle_id() {
        return $this->salle_id;
    }
}