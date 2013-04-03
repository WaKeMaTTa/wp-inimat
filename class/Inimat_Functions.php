<?php

/**
*   Class functions extra for plugin
 */

class Inimat_Functions
{
	
	/**
     *  Constructor of the class.
     *
     *  Initializes the class and the default properties.
     *
     *  @return void
     */
    function __construct() {

        // set the default base url
        $base_url = WPINIMAT_PLUGIN_URL . 'class/Inimat_Functions.php';

    }
	
	/**
     *  Show message error or correct
     *
     *  @return string
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
     *  Path relative
     *
     *  @return string
     */
	function path_relative() {
		$doc_root = str_ireplace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
		$plugin_path = str_ireplace('\\', '/', WPINIMAT_PLUGIN_PATH);
		$path_relative = str_ireplace($doc_root, '', $plugin_path);
		$path_relative = '/'.$path_relative;
		return $path_relative;
	}

	/**
	 *  Count total of creatures in database and return array with total.
	 *
	 *  @param  none 
	 *
	 *  @return array
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
	 *  Get a HTML of filter type creature
	 *
	 *  @param  string		$current	(Optional) This var indicate link current.
	 *
	 *  @return string $html
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
		
		/*
		$html = '<ul class="subsubsub">';		
		$html .= '<li><a href="' . WPINIMAT_CC_URL . '" ';
		$html .= $current == '' ? 'class="current" ' : '';
		$html .= ' >' . __('All', 'wpinimat_languages') . '&nbsp;<span class="count">(' . $n[0] . ')</span></a>&nbsp;|</li>';
		
		$html .= '<li><a href="' . WPINIMAT_CC_URL . '&type=material" ';
		$html .= $current == 'material' ? 'class="current" ' : '';
		$html .= ' >' . __('Material', 'wpinimat_languages') . '&nbsp;<span class="count">(' . $n[1] . ')</span></a>&nbsp;|</li>';
		
		$html .= '<li><a href="' . WPINIMAT_CC_URL . '&type=astral" ';
		$html .= $current == 'astral' ? 'class="current" ' : '';
		$html .= ' >' . __('Astral', 'wpinimat_languages') . '&nbsp;<span class="count">(' . $n[2] . ')</span></a>&nbsp;|</li>';
		
		$html .= '<li><a href="' . WPINIMAT_CC_URL . '&type=guardian" ';
		$html .= $current == 'guardian' ? 'class="current" ' : '';
		$html .= ' >' . __('Guardian', 'wpinimat_languages') . '&nbsp;<span class="count">(' . $n[3] . ')</span></a>&nbsp;|</li>';
		
		$html .= '<li><a href="' . WPINIMAT_CC_URL . '&type=samus" ';
		$html .= $current == 'samus' ? 'class="current" ' : '';
		$html .= ' >' . __('Samus', 'wpinimat_languages') . '&nbsp;<span class="count">(' . $n[4] . ')</span></a></li>';
		$html .= '</ul>';
		*/
		
		return $html;
	}

}

?>