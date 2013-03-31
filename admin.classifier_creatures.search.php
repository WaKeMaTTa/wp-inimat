<?php
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

// Includes
require_once( WPINIMAT_PLUGIN_PATH . 'class/zebra_form/Zebra_Form.php' );
require_once( WPINIMAT_PLUGIN_PATH . 'class/Class_Search.php' );

// var globlas
global $wpdb;

// instantiate a Zebra_Form object
$form_search = new Zebra_Form('form_search_creature');

// Language for errors form
if (WPLANG == 'es_ES') {
	$form_search->language('espanol');
}

$form_search->add('label', 'label_search_creature', 'search_creature', __('Search creature', 'wpinimat_languages'), array('inside' => true));

$obj = $form_search->add('text', 'search_creature');

$obj->set_rule(array(
	'required'  =>  array('error', 'Text is required!'),
));

$form_search->add('submit', 'btnsubmit', __('Search', 'wpinimat_languages'));

if ($form_search->validate()) {
	
	// show results
	print_r('<pre>');
	
	echo 'Result SQL:';
	print_r($result);
	
	echo 'Result POST:';
	print_r($_POST);
	die;
	
} else {
	
	$form_search->render();

}

?>