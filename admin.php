<?PHP
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}


// Action create admin menu of the plugin
add_action( 'admin_menu', 'wpinimat_admin_menu' );

// Show warnings messages
wpinimat_admin_warnings();

// Function prepare warnings messages panel control wordpress
function wpinimat_admin_warnings() {
	
	global $pagenow;
	
	if ( $pagenow == 'plugins.php' && isset($_GET['page']) == 'wpinimat' ) {
		
		if ( get_option( 'wpinimat_alert_code' ) ) {
			function wpinimat_alert() {
				$alert = array(
					'code'	=> (int) get_option( 'wpinimat_alert_code' ),
					'msg'	=> get_option( 'wpinimat_alert_msg' )
				);
			?>
				<div class='error'>
					<p><strong><?php echo WPINIMAT_NAME; ?> Error Code: <?php echo $alert['code']; ?></strong></p>
					<p><?php esc_html_e( $alert['msg'], 'wpinimat_languages' ); ?></p>
					<p>Report this error <a href="https://github.com/WaKeMaTTa/wp-inimat/issues">here</a></p>
				</div>
			<?php
			}

			add_action( 'admin_notices', 'wpinimat_alert' );
		}
	}
}

// Function check the version of wordpress and the plugin if all well, the plugin is ON.
function wpinimat_admin_init() {
	
	global $wp_version;
	
	// all admin functions are disabled in old versions
	if ( !function_exists('is_multisite') && version_compare( $wp_version, '3.0', '>=' ) ) {
		
		function wpbootcamp_version_warning() {
            echo "<div id='wpinimat-warning' class='updated fade'><p><strong>";
			sprintf( __( '%s %s requires WordPress 3.0 or higher.', 'wpinimat_languages' ), WPINIMAT_NAME, WPINIMAT_VERSION );
			echo "</strong> ";
			sprintf( __('Please <a href="%s">upgrade WordPress</a> to a current version.', 'wpinimat_languages' ), 'http://codex.wordpress.org/Upgrading_WordPress' );
			echo "</p></div>";
        }
		
		add_action( 'admin_notices', 'wpinimat_version_warning' ); 
        
        return; 
    }
}

// Activate the plugin configuration
add_action('admin_init', 'wpinimat_admin_init');

// Function create the admin menu
function wpinimat_admin_menu() {
	
	$page["main"] = add_menu_page(
		WPINIMAT_NAME,							# page_title
		WPINIMAT_NAME,							# menu_title
		'read',									# capability
		'wpinimat',								# menu_slug
		'wpinimat_admin_page',					# function (optional)
		WPINIMAT_PLUGIN_URL.'img/icon.x16.png'	# icon_url (optional)
	);
					
	
	$page["cc"] = add_submenu_page(
		'wpinimat',												# parent_slug
		__( 'Classifier of creatures', 'wpinimat_languages' ),	# page_title
		__( 'Classifier of creatures', 'wpinimat_languages' ),	# menu_title
		'read',													# capability
		'wpinimat/classifier_creatures',						# menu_slug
		'wpinimat_classifier_creatures'							# function (optional)
	);
	
	$page["cc_add"] = add_submenu_page(
		'wpinimat',
		__( 'Add ‹ Classifier of creatures', 'wpinimat_languages' ),
		__( 'Add a creature', 'wpinimat_languages' ),
		'read',
		'wpinimat/classifier_creatures/add',
		'wpinimat_classifier_creatures_add'
	);
								
	$page["cc_edit"] = add_submenu_page(
		'wpinimat',
		__( 'Edit ‹ Classifier of creatures', 'wpinimat_languages' ),
		__( 'Edit a creature', 'wpinimat_languages' ),
		'manage_options',
		'wpinimat/classifier_creatures/edit',
		'wpinimat_classifier_creatures_edit'
	);
								
	$page["cc_view"] = add_submenu_page(
		'wpinimat',
		__( 'View ‹ Classifier of creatures', 'wpinimat_languages' ),
		__( 'View a creature', 'wpinimat_languages' ),
		'read',
		'wpinimat/classifier_creatures/view',
		'wpinimat_classifier_creatures_view'
	);
	
	$page["settings"] = add_submenu_page(
		'wpinimat',
		__( 'Settings', 'wpinimat_languages' ),
		__( 'Settings', 'wpinimat_languages' ),
		'manage_options',
		'wpinimat/settings',
		'wpinimat_settings'
	);
	
	// Register CSS and Js			
	foreach ($page as &$value) {
		add_action( 'admin_print_styles-'.$value, 'wpinimat_admin_stylesheet' );
		add_action( 'admin_print_scripts-'.$value, 'wpinimat_admin_script' );
	}
	unset($value);

	return $page;										
}

// Style CSS
function wpinimat_admin_stylesheet() {
	wp_register_style( 'wp-inimat-admin-css', WPINIMAT_PLUGIN_URL . 'css/wp-inimat.admin.css', array(), '1.0', 'all');
	wp_enqueue_style( 'wp-inimat-admin-css' );
	
	wp_register_style( 'zebra_form-css', WPINIMAT_PLUGIN_URL . 'css/zebra_form.css', array(), '1.0', 'all');
	wp_enqueue_style( 'zebra_form-css' );
	
}

// Js JQuery
function wpinimat_admin_script() {
	wp_register_script( 'jquery-1-9-1-js', WPINIMAT_PLUGIN_URL . 'js/jquery-1.9.1.min.js', array(), '1.9.1');
	wp_enqueue_script( 'jquery-1-9-1-js' );
	
	wp_register_script( 'zebra_form-js', WPINIMAT_PLUGIN_URL . 'js/zebra_form.min.js', array(), '2.9.1');
	wp_enqueue_script( 'zebra_form-js' );
	
	wp_register_script( 'zebra_pagination-js', WPINIMAT_PLUGIN_URL . 'js/zebra_pagination.min.js', array(), '1.0');
	wp_enqueue_script( 'zebra_pagination-js' );
	
	wp_register_script( 'extra-js', WPINIMAT_PLUGIN_URL . 'js/extra.min.js', array(), '1.0');
	wp_enqueue_script( 'extra-js' );
}

// Function wpbootcamp_admin_home
function wpinimat_admin_page() {	
	echo '<div class="wrap">';
	echo WPINIMAT_ICON32 . '<h2>Inimat</h2>';
	require( plugin_dir_path(__FILE__) . 'admin.main.php');
	echo '</div>';
}

// Function for wpinimat_classifier_creatures the plugin
function wpinimat_classifier_creatures() {	
	echo '<div class="wrap">';
	echo WPINIMAT_ICON32 . '<h2>Inimat - ' . __( 'Classifier of creatures', 'wpinimat_languages' ) . '<a href="admin.php?page=wpinimat/classifier_creatures/add" class="add-new-h2">' . __('Add new', 'wpinimat_languages') . '</a></h2>';
	require( plugin_dir_path(__FILE__) . 'admin.classifier_creatures.php');
	echo '</div>';
}


// Function for wpinimat_classifier_creatures_add the plugin
function wpinimat_classifier_creatures_add() {
	echo '<div class="wrap">';
	echo WPINIMAT_ICON32 . '<h2>Inimat - ' . __( 'Classifier of creatures', 'wpinimat_languages' ) . '</h2>';
	echo '<h3>' . __('Add creature') . '</h3>';
	require( plugin_dir_path(__FILE__) . 'admin.classifier_creatures.add.php');
	echo '</div>';
}

// Function for wpinimat_classifier_creatures_edit the plugin
function wpinimat_classifier_creatures_edit() {
	echo '<div class="wrap">';
	echo WPINIMAT_ICON32 . '<h2>Inimat - ' . __( 'Classifier of creatures', 'wpinimat_languages' ) . '</h2>';
	echo '<h3>' . __('Edit creature') . '</h3>';
	require( plugin_dir_path(__FILE__) . 'admin.classifier_creatures.edit.php');
	echo '</div>';
}

// Function for wpinimat_classifier_creatures_edit the plugin
function wpinimat_classifier_creatures_view() {
	echo '<div class="wrap">';
	echo WPINIMAT_ICON32 . '<h2>Inimat - ' . __( 'Classifier of creatures', 'wpinimat_languages' ) . '</h2>';
	echo '<h3>' . __('View creature') . '</h3>';
	require( plugin_dir_path(__FILE__) . 'admin.classifier_creatures.view.php');
	echo '</div>';
}

// Function for settings the plugin
function wpinimat_settings() {
	echo '<div class="wrap">';
	echo WPINIMAT_ICON32 . '<h2>Inimat - ' . __( 'Settings', 'wpinimat_languages' ) . '</h2>';
	require( plugin_dir_path(__FILE__) . 'admin.settings.php');
	echo '</div>';
}

?>