<?php 

class Weapon 
{
    public $name;
    public $damage;
    public $levelRequired;

    public function __construct($name, $damage, $levelRequired) {
        $this->name = $name;
        $this->damage = $damage;
        $this->levelRequired = $levelRequired;
    }

    
}

?>