<?php
	 	session_start();
        
        if (isset($_GET['nomAliment'])) {
            $bdd=new PDO("mysql:host=localhost; dbname=nutribilan", "root", "");
            $nomAlim = $_GET['nomAliment'];
           
            $sql = !isset($_GET['exact']) ? "SELECT nomAliment FROM aliment WHERE nomAliment LIKE :nomAlim" : "SELECT nomAliment FROM aliment WHERE nomAliment = :nomAlim";
            $query=$bdd->prepare($sql);
            $stringToPrepare = !isset($_GET['exact']) ? "%$nomAlim%" : $nomAlim;
            $query->bindValue(":nomAlim", $stringToPrepare, PDO::PARAM_STR);
            $query->execute();

            $results = $query->fetchAll();

            if (!isset($_GET['exact'])) {

                if (count($results)>0) {
                    echo "<div style='border-bottom:1px solid grey; margin-bottom:10px'> Suggestions </div>";
                }
    
                foreach ($results as $tuple) {
                    $found = $tuple['nomAliment'];
                    echo "<div class='suggestion' onclick='changeTextInput(\"nomAliment\", \"$found\");'> $found </div>";
                }
            }
            else {
                if (count($results)>0) {
                    echo "found";

                    // variables de session pour enregistrer le tableau
                    $jour = $_GET['jour'];
                    $moment = $_GET['moment'];
                    $quantite = $_GET['quantite'];
                    $numero = $_GET['numero'];

                    $_SESSION[$jour][$moment]["aliment".$numero] = $_GET['nomAliment'] . "/" . $quantite;

                }
            }

        }

        exit;
?>
