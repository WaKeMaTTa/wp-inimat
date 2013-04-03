<?php
/*
Plugin Name: WP Inimat
Plugin URI: https://github.com/WaKeMaTTa/WP-Inimat
Description: WP Inimat
Version: 1.0
Author: WaKeMaTTa (Mohamed Ziata)
Email: m.ziata@hotmail.com
Author URI: https://github.com/WaKeMaTTa/
License: GPLv3 or later
Textdomain: wpinimat_languages
*/

/*  Copyright 2013  WaKeMaTTa  (email : m.ziata@hotmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 3, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Define the constants
define( 'WPINIMAT_NAME', 'Inimat' );
define( 'WPINIMAT_VERSION', '1.0' );
define( 'WPINIMAT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPINIMAT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPINIMAT_ICON32', '<div id="icon-themes" class="icon32" style="background-image:url('.WPINIMAT_PLUGIN_URL.'img/icon.x32.png); background-position: 0 0;"><br /></div>' );
define( 'WPINIMAT_CC_URL', admin_url('admin.php?page=wpinimat/classifier_creatures') );

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

// Function to activate the plugin
function wpinimat() {
	// Globals
	global $wpdb;
	
    // Loading textdomain
	load_plugin_textdomain( 'wpinimat_languages', false, dirname(plugin_basename( __FILE__ )).'/languages' );

	// Create table PREFIX_inimat_creatures to database		
	$query = $wpdb -> query(
		"CREATE TABLE IF NOT EXISTS `wp_inimat_creatures` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `date_add` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `date_edit` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `id_author` int(11) NOT NULL,
			  `name_author` varchar(50) NOT NULL,
			  `name` varchar(50) NOT NULL,
			  `height` float(6,2) NOT NULL,
			  `width` float(6,2) NOT NULL,
			  `weight` float(6,2) NOT NULL,
			  `type` varchar(50) NOT NULL,
			  `gender` varchar(50) NOT NULL,
			  `skills` text NOT NULL,
			  `habitat` text NOT NULL,
			  `description` text NOT NULL,
			  `sketch` text NOT NULL,
			  `modeled` text NOT NULL,
			  `textured` text NOT NULL,
			  `file` text NOT NULL,
			  `license` tinyint(1) NOT NULL,
			  `finished` tinyint(1) NOT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `name` (`name`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
	);
}

// Activate the plugin
add_action( 'init', 'wpinimat' );

// We include files
if ( is_admin() ) {
	require_once dirname( __FILE__ ) . '/admin.php';
}

/*
include_once dirname( __FILE__ ) . '/widget.php';

// Function to register widgets of the plugin
function wpinimat_register_widgets() {
	register_widget( 'wpbootcamp_widget' );
}

// Register widgets
add_action("widgets_init", "wpinimat_register_widgets");
*/

?>