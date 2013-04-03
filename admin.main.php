<?php
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}


// Includes
require_once( WPINIMAT_PLUGIN_PATH . 'class/Inimat_Functions.php' );

$functions = new Inimat_Functions();

// var globlas
global $wpdb, $current_user;

// Menu

$menu = wpinimat_admin_menu();

print_r('<pre>'); print_r($menu);

$menu = str_replace('inimat_page_', '', $menu);

$menu = str_replace('toplevel_page_', '', $menu);

print_r('<pre>'); print_r($menu);


?>