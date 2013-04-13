<?php

/**
*	Class functions extra for plugin
*/

class Inimat_Functions
{

	/**
	 *	Constructor of the class.
	 *
	 *	Initializes the class and the default properties.
	 *
	 *	@return void
	 */
	function __construct() {

		$base_url = WPINIMAT_PLUGIN_URL . 'class/Inimat_Functions.php';

	}

	/**
	 *	Show message error or correct
	 *
	 *	@return string
	 */
	function msg($msg, $type_msg) { // $type_msg => 'correct' or 'warning'
	
		echo '
		<div class="' . $type_msg . '">
			<div class="container">
				<span>'.$msg.'</span>
				<div class="' . $type_msg . '"><a href="javascript:void(0)">close</a></div>
			</div>
		</div>';

	}

	/**
	 *	Menu
	 *
	 *	@return array
	 */
	function menu() {

		$menu[0]["title"] = 'Classifier of creatures';
		$menu[0]["slug"] = 'wpinimat/classifier_creatures';
		$menu[0]["capability"] = 'read';

			$menu[0]["page"][0]["title"] = 'View the creatures';
			$menu[0]["page"][0]["slug"] = 'wpinimat/classifier_creatures';
			$menu[0]["page"][0]["capability"] = 'read';

			$menu[0]["page"][1]["title"] = 'Add a creature';
			$menu[0]["page"][1]["slug"] = 'wpinimat/classifier_creatures/add';
			$menu[0]["page"][1]["capability"] = 'read';

			$menu[0]["page"][2]["title"] = 'Edit a creature';
			$menu[0]["page"][2]["slug"] = 'wpinimat/classifier_creatures/edit';
			$menu[0]["page"][2]["capability"] = 'manage_options';

			$menu[0]["page"][3]["title"] = 'View the creature';
			$menu[0]["page"][3]["slug"] = 'wpinimat/classifier_creatures';
			$menu[0]["page"][3]["capability"] = 'read';

		$menu[1]["title"] = 'Settings';
		$menu[1]["slug"] = 'wpinimat/settings';
		$menu[1]["capability"] = 'manage_options';

			$menu[1]["page"][0]["title"] = 'Settings';
			$menu[1]["page"][0]["slug"] = 'wpinimat/settings';
			$menu[1]["page"][0]["capability"] = 'manage_options';

		return $menu;

	}

	/**
	 *	Path relative
	 *
	 *	@return string
	 */
	function path_relative() {
		$doc_root = str_ireplace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
		$plugin_path = str_ireplace('\\', '/', WPINIMAT_PLUGIN_PATH);
		$path_relative = str_ireplace($doc_root, '', $plugin_path);
		$path_relative = '/'.$path_relative;
		return $path_relative;
	}

	/**
	 *	Count total of creatures in database and return array with total.
	 *
	 *	@return array
	 */
	function count_creatures() {
		// Globla var DateBase
		global $wpdb;
		// All types creatures
		$return[0] = $wpdb->query("SELECT id FROM " . $wpdb->prefix . "inimat_creatures");
		
		// Material types creatures
		$return[1] = $wpdb->query("SELECT id FROM " . $wpdb->prefix . "inimat_creatures WHERE type = 'material'");
		
		// Astral types creatures
		$return[2] = $wpdb->query("SELECT id FROM " . $wpdb->prefix . "inimat_creatures WHERE type = 'astral'");
		
		// Guardian types creatures
		$return[3] = $wpdb->query("SELECT id FROM " . $wpdb->prefix . "inimat_creatures WHERE type = 'guardian'");
		
		// Samus types creatures
		$return[4] = $wpdb->query("SELECT id FROM " . $wpdb->prefix . "inimat_creatures WHERE type = 'samus'");
		
		return $return;
	}
	
	/**
	 *	Get a HTML of filter type creature
	 *
	 *	@param  string		$current	(Optional) This var indicate link current.
	 *
	 *	@return string 		$html
	 */
	function html_filter_type($current = '') {
		$n = $this->count_creatures();
		
		$html = '<ul class="subsubsub">';
				
		$html .= '<li><a>' . __('All', 'wpinimat_languages') . '</a>&nbsp;<span class="count">(' . $n[0] . ')</span></a>&nbsp;|&nbsp;</li>';
		
		$html .= '<li><a>' . __('Material', 'wpinimat_languages') . '</a>&nbsp;<span class="count">(' . $n[1] . ')</span>&nbsp;|&nbsp;</li>';
		
		$html .= '<li><a>' . __('Astral', 'wpinimat_languages') . '</a>&nbsp;<span class="count">(' . $n[2] . ')</span>&nbsp;|&nbsp;</li>';
		
		$html .= '<li><a>' . __('Guardian', 'wpinimat_languages') . '</a>&nbsp;<span class="count">(' . $n[3] . ')</span>&nbsp;|&nbsp;</li>';
		
		$html .= '<li><a>' . __('Samus', 'wpinimat_languages') . '</a>&nbsp;<span class="count">(' . $n[4] . ')</span></li>';
		
		$html .= '</ul>';
		
		return $html;
	}

}

?>