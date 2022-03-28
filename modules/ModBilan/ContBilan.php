<?php  
    if (!defined('CONSTANTE'))
        die("Accès interdit");

        require_once "ModeleBilan.php";
        require_once "VueBilan.php";

       class ContBilan {

            public $modele;
            public $vue;

            public function __construct() {
                $this->modele = new ModeleBilan();
                $this->vue = new VueBilan();
            }

            public function formulaireBilan(){
                $this->vue->formulaireBilan();
            }

            
            public function calculBilan($pdf) {

                if (!$pdf) {
                    $this->vue->afficherBilan($this->modele->preparerBilan());
                }
                else {
                    $this->vue->afficherPdf($this->modele->preparerBilan());
                }
                
                
            }

            
        
        }

?>

