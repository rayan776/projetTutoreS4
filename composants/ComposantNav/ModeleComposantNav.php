<?php

	if (!defined('CONSTANTE'))
		die("Accès interdit");
		
	class ModeleComposantNav extends ConnectDB {
	
		public function __construct() {
			parent::connect();
		}
		
		public function menuModAccueil() {
			
			$liens=array();
			
			$liens["index.php"]="Accueil";
			$liens["index.php?module=ModBilan&action=realiserBilan"]="Réalisez votre bilan nutrition";
			$liens["index.php?module=ModAccueil&action=aboutUs"]="A propos de nous";
			
			return $liens;
		}

		public function menuModBilan() {
			$liens=array();


			return $liens;
		}
		
		
	}
	
?>
