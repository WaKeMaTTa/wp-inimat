<?php
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}


// Includes
require_once( WPINIMAT_PLUGIN_PATH . 'class/Inimat_Functions.php' );

$functions = new Inimat_Functions();

$menu = $functions->menu();

foreach ($menu as $key => $value) {

	if (current_user_can( $menu[$key]["capability"] ) == TRUE) {

		?>

		<div id="welcome-panel" class="welcome-panel">

			<div class="welcome-panel-content">

				<h3><?php echo $menu[$key]["title"]; ?></h3>

				<?php

				$pages = count($menu[$key]["page"]);

				for ($i=0; $i<$pages; $i++) {

					if (current_user_can( $menu[$key]["page"][$i]["capability"] ) == TRUE)

						echo '<a class="button button-primary button-hero" href="?page=' . $menu[$key]["page"][$i]["slug"] . '">' . $menu[$key]["page"][$i]["title"] . '</a>&nbsp';

					}

				?>
			
			</div>

		</div>

		<?php

	}

}

// debug mode
if (WP_DEBUG == TRUE) {
	echo '<h3>Debug Mode:</h3>';
	print_r('<pre>');
	print_r($menu);
	die();
}

?>