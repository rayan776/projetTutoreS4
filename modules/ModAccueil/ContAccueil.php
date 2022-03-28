<?php  
    if (!defined('CONSTANTE'))
        die("Accès interdit");

        require_once "ModeleAccueil.php";
        require_once "VueAccueil.php";

       class ContAccueil {

            public $modele;
            public $vue;

            public function __construct() {
                $this->modele = new ModeleAccueil();
                $this->vue = new VueAccueil();
            }

            public function bienvenue() {
                $this->vue->bienvenue();
            }

            public function aboutUs() {
                $this->vue->aboutUs();
            }
        }

?>

