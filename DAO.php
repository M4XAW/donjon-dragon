<?php 

class DAO 
{
    public $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function choixPersonnage() {
        echo "\033[2J\033[;H";

        echo "Voici la liste des personnages\n\n";
        $sql = $this->db->prepare("SELECT * FROM personnages");
        $sql->execute();
        $result = $sql->fetchAll();

        foreach($result as $personnage) {
            echo $personnage["id"] . ". " . $personnage["nom"] . "\n";
        }

        if (count($result) == 0) {
            echo "Aucun personnage\n";
        }

        $choix = readline();

        switch($choix) 

        return $personnage;
    }
}

?>