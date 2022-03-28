<?php
	define('CONSTANTE', NULL);

	session_start();

	if (!isset($_SESSION["jours"])) {
		$_SESSION["jours"]=array("lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi", "dimanche");
		$_SESSION["moments"] = array("Matin", "Midi", "Soir");
	}

	if (!isset($_SESSION['lundi'])) {
		foreach ($_SESSION["jours"] as $jour) {
			$_SESSION[$jour] = array();
			foreach ($_SESSION["moments"] as $moment) {
				$_SESSION[$jour][$moment] = array();
			}
		}
	}

	require_once("fpdf184/fpdf.php");
	require_once("models/connectDB.php");
	require_once("models/tokens.php");
	require_once("vues/VueGenerique.php");
	require_once("composants/ComposantHeaderFooter/ComposantHeaderFooter.php");
	require_once("composants/ComposantNav/ComposantNav.php");
	
	$titrePage = "Nutri-Bilan";
	
	if (!array_key_exists('token', $_SESSION)) // distribution d'un token de session, si il n'y en a pas déjà un
		$_SESSION['token']=Tokens::generateToken();
	
	if (isset($_GET['module'])) {
		$module=$_GET['module'];
		
		switch ($module) {
			case "ModAccueil":
				break;
			case "ModAdmin":
				$titrePage = "Menu administrateur";
				break;
			case "ModBilan":
				$titrePage = "Réalisez votre bilan";
				break;
			default:
				die("Accès interdit");
		}
	}
	else {
		$module = "ModAccueil";
	}
	
	$urlMod="modules/" . $module . "/" . $module . ".php";
	
	require_once($urlMod);
	$mod=new $module();
	
	$affichage=$mod->controleur->vue->getAffichage();
	
	$compNav = new ComposantNav();
	
	$liensMenuPrincipal = $compNav->getMenu("ModAccueil");
	
	if ($module != "ModAccueil") {
		$compNav2 = new ComposantNav();
		$liensMenuSecondaire = $compNav2->getMenu($module);
	}
	
	$compHeader = new ComposantHeaderFooter();
	$header = $compHeader->getHeader();
	$compFooter = new ComposantHeaderFooter();
	$footer = $compFooter->getFooter();

	$templateSecondaire = "templates/template" . $module . ".php";
	
	require_once "templates/templatePrincipal.php";
	
?>
