<?php
	if (!defined('CONSTANTE'))
		die("Accès interdit");
		
	class VueComposantNav extends VueGenerique {
		
		public function __construct() {
			parent::__construct();
		}

		public function menu($tab, $nomMenu) {
			
			?>
		
			<div class='menu navbar' id='<?=$nomMenu ?>'>
				<?php foreach ($tab as $lien => $nom): 
				
				
				?>
					<a id="nav<?=$nom?>" href='<?=$lien ?>'> <?=$nom?> </a>
				<?php endforeach; ?>
			</div>
		
			<?php
		}
	}
?>
