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

        switch($choix) {
            case 1:
                $personnage = $this->getPersonnage(1);
                break;
            case 2:
                $personnage = $this->getPersonnage(2);
                break;
            case 3:
                $personnage = $this->getPersonnage(3);
                break;
            case 4:
                $personnage = $this->getPersonnage(4);
                break;
            default:
                echo "Ce personnage n'existe pas\n";
                exit;
        }

        return $personnage;
    }

    public function getPersonnage($id) {
        $sql = $this->db->prepare("SELECT * FROM personnages WHERE id = :id");
        $sql->execute([
            "id" => $id
        ]);
        $result = $sql->fetch();
        echo "\033[2J\033[;H";

        echo "Vous avez choisi " . $result["nom"] . "\n";
        return $result;
    }
}

?>