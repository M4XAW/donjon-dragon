<?php 

include("config.php");
include("DAO.php");

class Character 
{
    public $name;
    public $health;
    public $attackPoints;
    public $defensePoints;
    public $xp;
    public $level;

    public function __construct($name, $health, $attackPoints, $defensePoints) {
        $this->name = $name;
        $this->health = $health;
        $this->attackPoints = $attackPoints;
        $this->defensePoints = $defensePoints;
        $this->experience = 0;
        $this->level = 1;
    }
}

?>