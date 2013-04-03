<?php
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

// Includes
require_once( WPINIMAT_PLUGIN_PATH . 'class/zebra_form/Zebra_Form.php' );
require_once( WPINIMAT_PLUGIN_PATH . 'class/Inimat_Functions.php' );

$functions = new Inimat_Functions();

// var globlas
global $wpdb, $current_user;

if (isset($_GET["select_creature"]) == FALSE) {
	
	// instantiate a Zebra_Form object
	//$form = new Zebra_Form('form', 'GET');
	$form = new Zebra_Form('form', 'GET');
	
	// Language for errors form
	if (WPLANG == 'es_ES') {
		$form->language('espanol');
	}
	
	// select criature for edit them
	
	$obj = $form->add('hidden', 'page', 'wpinimat/classifier_creatures/view');
	
	$form->add('label', 'label_select_creature', 'select_creature', __('Select the creature to edit:', 'wpinimat_languages'));
	
	$obj = $form->add('select', 'select_creature', '', array('style' => 'height: 28px;'));
	
	$creatures = $wpdb->get_results ("SELECT id, name FROM ".$wpdb->prefix."inimat_creatures ORDER BY id ASC", ARRAY_A );
	
	$array_asosiativo = array();
	
	for($i=0; $i<$wpdb->num_rows; $i++) {
		$array_asosiativo[$i] = $creatures[$i]["name"];
	}
	
	$obj->add_options($array_asosiativo);
	$obj->set_rule(array(
	
		'required' => array('error', 'Select creature is required!')
		
	));

    $form->add('submit', 'btnsubmit', __('Submit', 'wpinimat_languages'));
	
	// validate the form
	if($form->validate()) { }
	
	// auto generate output, labels above form elements
	$form->render();

} elseif ($wpdb->query( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."inimat_creatures WHERE id = %d", $_GET["select_creature"]+1) ) != FALSE) {

	// recover ID creature
	$id_creature = $_GET["select_creature"] + 1;
	
	// recover data from the database of the creature
	$sql = $wpdb->get_results ("SELECT * FROM ".$wpdb->prefix."inimat_creatures WHERE id = ".$id_creature, ARRAY_A );

	if ($sql[0]["textured"] != '') {
						
		$textured = unserialize($sql[0]["textured"]);
		$img = WPINIMAT_PLUGIN_URL . 'upload/' . $textured["file_name"];
		
	} elseif ($sql[0]["modeled"] != '') {
		
		$modeled = unserialize($sql[0]["modeled"]);
		$img = WPINIMAT_PLUGIN_URL . 'upload/' . $modeled["file_name"];
		
	} elseif ($sql[0]["sketch"] != '') {
		
		$sketch = unserialize($sql[0]["sketch"]);
		$img = WPINIMAT_PLUGIN_URL . 'upload/' . $sketch["file_name"];
		
	} else {
		
		// no image
		$img = WPINIMAT_PLUGIN_URL . 'img/not_img.png';
		
	}

	$skills = unserialize($sql[0]["skills"]);

	?>

	<div id="creature">

		<img class="img" src="<?php echo $img; ?>" width="280" height="280" />

		<p class="title"><?php echo ucfirst(strtolower($sql[0]["name"])); ?></p>

		<p class="sub_title"><?php _e('Characteristics', 'wpinimat_languages'); ?></p>

		<div class="characteristics">

			<?php echo '<p><b>' . __('Height', 'wpinimat_languages') . '</b> ' . $sql[0]["height"] . ' ' . __('meters', 'wpinimat_languages') . '</p>'; ?>

			<?php echo '<p><b>' . __('Width', 'wpinimat_languages') . '</b> ' . $sql[0]["width"] . ' ' . __('meters', 'wpinimat_languages') . '</p>'; ?>

			<?php echo '<p><b>' . __('Weight', 'wpinimat_languages') . '</b> ' . $sql[0]["weight"] . ' ' . __('Kg', 'wpinimat_languages') . '</p>'; ?>

			<?php echo '<p><b>' . __('Type', 'wpinimat_languages') . '</b> <span class="type ' . $sql[0]["type"] . '">' . $sql[0]["type"] . '</span></p>'; ?>

			<?php echo '<p><b>' . __('Gender', 'wpinimat_languages') . '</b> <span class="gender ' . $sql[0]["gender"] . '">' . $sql[0]["gender"] . '</span></p>'; ?>

			<?php echo '<p><b>' . __('Habitat', 'wpinimat_languages') . '</b> ' . ucfirst(strtolower($sql[0]["habitat"])) . '</p>'; ?>

		</div>

		<br />

		<p class="sub_title"><?php _e('Skills', 'wpinimat_languages'); ?></p>

		<div class="skills">

		<?php

		foreach ($skills as $key => $value) {

			$skill_name = ucfirst(strtolower($skills[$key][0]));

			$skill_type = ($skills[$key][1] == 'attack') ? __('Attack', 'wpinimat_languages') : __('Defense', 'wpinimat_languages');

			$skill_desc = ucfirst(strtolower($skills[$key][2]));

			echo '<p>' . $skill_type . '<span class="' . $skills[$key][1] . '">' . $skill_name 
				.' <img src="' . WPINIMAT_PLUGIN_URL . 'img/info.x16.png" title="' . $skill_desc . '"></span></p>';

		}

		?>

		</div>

		<p class="sub_title"><?php _e('Description', 'wpinimat_languages'); ?></p>

		<div class="description">

			<?php echo ucfirst(strtolower($sql[0]["description"])); ?>

		</div>

		<br />

		<p class="sub_title"><?php _e('Files', 'wpinimat_languages'); ?></p>

		<div class="files">

		<table>

			<tr>

				<?php

				if (isset($sketch)) {

					echo '<td><a target="_blank" href="' . WPINIMAT_PLUGIN_URL . 'upload/' . $sketch["file_name"] . '">';
					echo '<img src="' . WPINIMAT_PLUGIN_URL . 'upload/th/' . $sketch["file_name"] . '" /></a></td>';
				
				} else {

					echo '<td><img src="' . WPINIMAT_PLUGIN_URL . 'img/not-img.png" width="100" height="100" /></td>';

				}

				if (isset($modeled)) {

					echo '<td><a target="_blank" href="' . WPINIMAT_PLUGIN_URL . 'upload/' . $modeled["file_name"] . '">';
					echo '<img src="' . WPINIMAT_PLUGIN_URL . 'upload/th/' . $modeled["file_name"] . '" /></a></td>';
				
				} else {

					echo '<td><img src="' . WPINIMAT_PLUGIN_URL . 'img/not-img.png" width="100" height="100" /></td>';

				}

				if (isset($textured)) {

					echo '<td><a target="_blank" href="' . WPINIMAT_PLUGIN_URL . 'upload/' . $textured["file_name"] . '">';
					echo '<img src="' . WPINIMAT_PLUGIN_URL . 'upload/th/' . $textured["file_name"] . '" /></a></td>';
				
				} else {

					echo '<td><img src="' . WPINIMAT_PLUGIN_URL . 'img/not-img.png" width="100" height="100" /></td>';

				}

				if ($sql[0]["file"] != '') {

					$file = unserialize($sql[0]["file"]);

					echo '<td><a target="_blank" href="' . WPINIMAT_PLUGIN_URL . 'upload/' . $file["file_name"] . '">';
					echo '<img src="' . WPINIMAT_PLUGIN_URL . 'img/zip.png" /></a></td>';
				
				} else {

					echo '<td><img src="' . WPINIMAT_PLUGIN_URL . 'img/not-img.png" width="100" height="100" /></td>';

				}

				?>

			</tr>

			<tr>

				<td><?php _e('Sketch', 'wpinimat_languages'); ?></td>

				<td><?php _e('Modeled', 'wpinimat_languages'); ?></td>

				<td><?php _e('Textured', 'wpinimat_languages'); ?></td>

				<td><?php _e('File Blender', 'wpinimat_languages'); ?></td>

			</tr>

		</table>

		</div>

	</div>

	<?php
	
} else {
	
	// error not found creature
	$functions->msg(__('Not found creature.', 'wpinimat_languages'), 'warning');
	
}

?>