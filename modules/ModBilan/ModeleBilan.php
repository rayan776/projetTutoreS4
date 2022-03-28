<?php  
    if (!defined('CONSTANTE'))
        die("Accès interdit");

        require_once "CalculateurBilan.php";

        class ModeleBilan extends ConnectDB {
            
            public $calculateur;

            public function __construct() {
                parent::connect();
                $this->calculateur = new CalculateurBilan();
            }

            public function preparerBilan() {

                // vérification de la requête POST
                $verif = $this->calculateur->verifierRequetePost();

                // si on trouve des erreurs
                if (count($verif)>0) {
                    return array (1, $verif);
                }
                
                
                // calcul du bilan nutritionnel
                return array (0, $this->calculateur->calculerBilan());

            }


        }


?>