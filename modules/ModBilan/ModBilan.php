<?php  
    if (!defined('CONSTANTE'))
        die("Accès interdit");

        require_once "ContBilan.php";

        class ModBilan {

            public $controleur;

            public function __construct() {
                $this->controleur = new ContBilan();

                $action = (isset($_GET['action'])) ? $_GET['action'] : "";

                switch ($action) {
                    case "calculerBilan":
                        if (isset($_GET['pdf'])&&$_GET['pdf']=="no") {
                            $this->controleur->calculBilan(false);
                        }
                        elseif (isset($_GET['pdf'])) {
                            $this->controleur->calculBilan(true);
                        }
                        break;
                   case "realiserBilan":
                    default:
                        $this->controleur->formulaireBilan();
                        
                }
            }

            

        }

?>

