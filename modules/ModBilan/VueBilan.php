<?php  
    if (!defined('CONSTANTE'))
        die("Accès interdit");

        require_once "GenerateurBilan.php";

        class VueBilan extends VueGenerique {

            public $generateur;

            public function __construct() {
                parent::__construct();
                $this->generateur = new GenerateurBilan();
            }

            public function formulaireBilan() {
                require_once "formulaireBilan.php";
            }

            public function afficherBilan($resultats) {
                echo $this->generateur->getHtml($resultats);
            }

            public function afficherPdf($resultats) {
                $this->generateur->bilanToPdf($resultats);
            }


        }

?>

