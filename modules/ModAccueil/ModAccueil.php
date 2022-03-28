<?php  
    if (!defined('CONSTANTE'))
        die("Accès interdit");

        require_once "ContAccueil.php";

        class ModAccueil {

            public $controleur;

            public function __construct() {
                $this->controleur = new ContAccueil();

                $action = (isset($_GET['action'])) ? $_GET['action'] : "";

                switch ($action) {
                    case "aboutUs":
                        $this->controleur->aboutUs();
                        break;
                    default:
                        $this->controleur->bienvenue();
                }
            }

            

        }

?>

