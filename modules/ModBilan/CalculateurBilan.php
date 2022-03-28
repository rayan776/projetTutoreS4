<?php  
    if (!defined('CONSTANTE'))
        die("Accès interdit");

        class CalculateurBilan extends ConnectDB  {

            public $listAliments;

            public function __construct() {
                parent::connect();
    
                $this->listAliments=$this->getListeAliments();
            }

            public function getListeAliments() {
                $sql="SELECT nomAliment FROM aliment";
                $query=parent::$bdd->prepare($sql);
                $query->execute();
                return $query->fetchAll();
            }

            public function alimentExiste($nomAliment) {
                foreach ($this->listAliments as $tuple) {
                    if (strcmp($tuple['nomAliment'], $nomAliment)==0) {
                        return 1;
                    }
                }

                return 0;
            }

            public function getBesoinsByGroupe($idGroupe) {
                $sql="SELECT nomNutri, 7*quantite AS quantite, unite FROM groupe INNER JOIN besoins USING (idGroupe) INNER JOIN nutriment USING (idNutri) WHERE idGroupe = $idGroupe";
                $query=parent::$bdd->prepare($sql);
                $query->execute();
                return $query->fetchAll();
            }

            public function getAllNutriments() {
                $sql="SELECT nomNutri FROM nutriment";
                $query=parent::$bdd->prepare($sql);
                $query->execute();
                return $query->fetchAll();
            }

            public function valeurNutritionnelle($aliment) {
                $sql="SELECT nomNutri, quantite, unite FROM contenir INNER JOIN aliment USING (idAliment) INNER JOIN nutriment USING (idNutri) WHERE nomAliment = :nomAliment";
                $query=parent::$bdd->prepare($sql);
                $query->bindValue(":nomAliment", $aliment, PDO::PARAM_STR);
                $query->execute();
                return $query->fetchAll();
            }

            public function verifierRequetePost() {
                // vérifier âge et sexe
                // il ne doit pas y avoir d'aliment inconnu dans la base de données
                // il ne doit pas y avoir de quantité indéterminée (c'est soit peu, soit moyen, soit beaucoup)

                $errors = array();
                
                // vérification âge
                if (!isset($_POST['age'])||empty(trim($_POST['age']))) {
                    $errors[] = "Âge indéfini.";
                    return $errors;
                }

                $age=$_POST['age'];

                // l'âge doit être un nombre entier strictement positif
                if (intval($_POST['age'])==0||!ctype_digit($age)) {
                    $errors[] = "Âge incorrect.";
                    return $errors;
                }

                // vérification sexe
                if (!isset($_POST['sexe'])) {
                    $errors[] = "Erreur.";
                    return $errors;
                }

                if ($_POST['sexe']!="male"&&$_POST['sexe']!="female") {
                    $errors[] = "Sexe invalide.";
                    return $errors;
                }

                $jours = array("lundi","mardi","mercredi","jeudi","vendredi","samedi","dimanche");
                $moments = array("Matin","Midi","Soir");
                $quantites = array("Peu","Moyen","Beaucoup");

                $stringAliment=array();

                foreach ($jours as $jour) {
                    if (isset($_POST[$jour])&&is_array($_POST[$jour])) {
                        foreach ($moments as $moment) {
                            if (array_key_exists($moment, $_POST[$jour])) {
                                if (is_array($_POST[$jour][$moment])) {

                                    foreach ($_POST[$jour][$moment] as $stringsAliments) {
                                        $stringAliment=explode("/", $stringsAliments);

                                        if (count($stringAliment)!=2) {
                                            $errors[]="Erreur.";
                                            return $errors;
                                        }
        
                                        if (!$this->alimentExiste($stringAliment[0])) {
                                            $errors[]="Vous avez placé au moins un aliment inexistant.";
                                            return $errors;
                                        }
        
                                        if (!in_array($stringAliment[1],$quantites)) {
                                            $errors[]="Quantité invalide";
                                            return $errors;
                                        }
                                    }
                                    
                                }
                                else {
                                    $errors[]="Erreur.";
                                    return $errors;
                                }
                               

                            }
                        }
                    }
                }

                // ok
                return $errors;
             
            }

            public function calculerBilan() {
                // la requête POST est sous la forme de 7 tableaux, chacun ayant le nom d'un jour de la semaine
                // chacun de ces tableaux possède trois clés: matin, midi, et soir
                // ces trois clés sont associées à une valeur correspondant à une chaîne de caractères du style: Aliment/Quantité

                // si un tableau n'existe pas, on considère qu'il n'existe aucun aliment n'a été placé à cet endroit.
                // on récupère, pour chaque aliment, ses valeurs nutritionnelles stockées dans la base de données pour procéder au calcul

                // comparer la quantité de nutriments du client avec les normes
                // retourner un tableau avec les proportions par rapport aux normes


                // étape 1: il faut déterminer le groupe auquel appartient le client (voir base de données)

                $age = intval($_POST['age']);
                $sexe = $_POST['sexe'];
                $idGroupe;

                if ($age>=1&&$age<=3) {
                    $idGroupe=3;
                }
                elseif ($age<=6) {
                    $idGroupe=4;
                }
                elseif ($age<=10) {
                    $idGroupe=5;
                }
                
                if ($sexe=="male") {
                    if ($age<=14) {
                        $idGroupe=6;
                    }
                    elseif ($age<=17) {
                        $idGroupe=7;
                    }
                    else {
                        $idGroupe=10;
                    }
                }
                else {
                    if ($age<=14) {
                        $idGroupe=8;
                    }
                    elseif ($age<=17) {
                        $idGroupe=9;
                    }
                    else {
                        $idGroupe=11;
                    }
                }

                // étape 2: une fois qu'on a le groupe, on récupère les besoins journaliers de celui-ci

                $besoins = $this->getBesoinsByGroupe($idGroupe);

                // étape 3: on a un tableau qui contient les quantités de nutriments consommées par le client dans la semaine avec
                // des pairs "nutriment -> quantite", du genre [magnesium]=100, [zinc]=50, etc. L'unité est le mg.
                
                // pour chaque aliment choisi par le client, à partir de sa quantité, on ajoute une valeur aux variables du tableau
                // par exemple: si il choisit "banane: beaucoup", alors on rajoute les nutriments que contiennent 80g de bananes
                // moyen -> 40g, peu -> 20g
                
                // il faut donc récupérer la quantité contenue par tel ou tel aliment de la base de données, puis appliquer un
                // facteur de réduction en fonction de la quantité, puis faire la somme dans le tableau

                $nutrimentsConsommes = array();
                $nomsNutriments = $this->getAllNutriments();

                foreach ($nomsNutriments as $nutri)
                    $nutrimentsConsommes[$nutri["nomNutri"]]=0;

                // faire la liste de tous les aliments choisis par le client, puis récupérer leur apport nutritionnel

                $jours = array("lundi","mardi","mercredi","jeudi","vendredi","samedi","dimanche");
                $moments = array("Matin","Midi","Soir");
                $coeffs = array("Peu"=>0.2,"Moyen"=>0.4,"Beaucoup"=>0.8);
                $coeff=0;

                $alimentsChoisis = array();
                $stringAliment = "";

                foreach ($jours as $jour) {
                    foreach ($moments as $moment) {
                        if (isset($_POST[$jour])&&is_array($_POST[$jour])) {
                            if (array_key_exists($moment, $_POST[$jour])) {

                                foreach ($_POST[$jour][$moment] as $stringsAliments) {
                                    $stringAliment=explode("/", $stringsAliments);
                                
                                    if (!in_array($stringAliment[0], $alimentsChoisis)) {
                                        $alimentsChoisis[$stringAliment[0]] = $this->valeurNutritionnelle($stringAliment[0]);
                                    }

                                    $coeff=$coeffs[$stringAliment[1]];

                                    foreach ($alimentsChoisis[$stringAliment[0]] as $tuple) {    
                                        $nutrimentsConsommes[$tuple['nomNutri']] += $tuple['quantite'] * $coeff;
                                    }
                                }
                               
                            }
                        }
                    }
                }

                $proportions=array();

                foreach ($besoins as $tuple) {
                    $proportions[$tuple['nomNutri']]=$nutrimentsConsommes[$tuple['nomNutri']]/$tuple['quantite'];
                }

                return $proportions;
                

            }

        }

?>