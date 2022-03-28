<?php  
    if (!defined('CONSTANTE'))
        die("Accès interdit");

        class VueAccueil extends VueGenerique {

            public function __construct() {
                parent::__construct();
            }

            public function bienvenue() {
                ?> <div style="margin-top:30px" class='content'> Bienvenue sur Nutri-Bilan </div> <?php
            }

            public function aboutUs() {
                require_once "aboutUs.php";
            }

        }

?>

