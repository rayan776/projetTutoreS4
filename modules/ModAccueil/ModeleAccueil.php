<?php  
    if (!defined('CONSTANTE'))
        die("Accès interdit");


        class ModeleAccueil extends ConnectDB {
            
            public function __construct() {
                parent::connect();
            }


        }


?>