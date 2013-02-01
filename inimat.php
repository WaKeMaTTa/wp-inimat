<?php
/*
Plugin Name: WP Inimat
Plugin URI: https://github.com/WaKeMaTTa/WP-Inimat
Description: WP Inimat
Version: 0.0.3.7
Author: WaKeMaTTa (Mohamed Ziata)
Email: m.ziata@hotmail.com
Author URI: https://github.com/WaKeMaTTa/
License: GPLv3
*/


global $inimat;
if(! class_exists( "inimat" ) ){
	
	class inimat {
		static $option_name = "inimat_options";
		static $database_prefix = "inimat_functions";
		static $post_prefix = "z29fno22lk32";
		public $_shared = "";
		static $database_version = "1";
	
	// variable estatica para indicar el nombre del archivo css
		static $css = '';
	
	// variable estatica para indicar el nombre del archivo css
		static $slug_stylesheet = "inimat-style";
		static $name_stylesheet = "style.css";
		
	// variables estaticas de permiso
		static $only_admins = "manage_options";
		static $all = "read";
	
	// database
		static $db_criaturas = "inimat_criaturas";

	// Menu Prinpial
		static $slug_principal = "inimat";
		static $menu_principal = "Inimat"; 
		static $page_principal = "Inimat";
		static $icon = '<div id="icon-themes" class="icon32" style="background-image:url(../wp-content/plugins/inimat/img/icon32.png); background-position: 0 0;"><br /></div>';
		
	// SubMenus
		static $slug_anadir_criatura 	= "inimat-anadir-criatura";
		static $title_anadir_criatura 	= "Añadir una criatura";
		
		static $slug_modificar_criatura 	= "inimat-modificar-criatura";
		static $title_modificar_criatura 	= "Modificar una criatura";
		
		static $slug_ver_criatura 		= "inimat-ver-criaturas";
		static $title_ver_criatura 		= "Ver las criaturas";
		
		static $submenu_20_slug = "inimat-menu";
		static $submenu_20_title = "Información del Plugin"; 
		static $submenu_20_page_title = "Información del Plugin";

		function __construct(){
			// Hook : 
			$this -> shared = array();
			// add_action($tag, $function_to_add, $priority, $accepted_args) - funcion de wordpress, se encuentra a wp-admin/includes/plugin.php
			add_action( "admin_menu", array( __CLASS__, "registrar_menu" ), 1 );
//ESTILO:	add_action( 'admin_init', array( __CLASS__, "menu_register_extras"),0);	
			// add_filter($tag, $function_to_add, $priority, $accepted_args) - funcion de wordpress, se encuentra a wp-admin/includes/plugin.php
//Filtor de texto		add_filter('widget_text', 'do_shortcode');
//Filtor de texto		add_filter('the_content', array( __CLASS__, "shortcode_advanced" ),0);
		}
		
/*ESTILO:function menu_register_extras(){
			wp_register_style( self::$stylesheet_slug, plugins_url('additional-styles.css', __FILE__) );
		}*/
		
		// menu_register() - funcion de wordpress, se encuentra a wp-admin/includes/plugin.php
		function registrar_menu(){
		// menu
			$page = add_menu_page( self::$page_principal, self::$menu_principal, self::$all, self::$slug_principal, array(__CLASS__, "page_principal"), plugins_url("img/icon.png", __FILE__), 3 );
		// submenus
			$parent_slug = self::$slug_principal;
			$page_anadir_criatura = add_submenu_page( $parent_slug, self::$title_anadir_criatura, self::$title_anadir_criatura, self::$all, self::$slug_anadir_criatura, array(__CLASS__, "page_anadir_criatura"));
			
			$page_modificar_criatura = add_submenu_page( $parent_slug, self::$title_modificar_criatura, self::$title_modificar_criatura, self::$all, self::$slug_modificar_criatura, array(__CLASS__, "page_modificar_criatura"));
			
			$page_ver_criatura = add_submenu_page( $parent_slug, self::$title_ver_criatura, self::$title_ver_criatura, self::$all, self::$slug_ver_criatura, array(__CLASS__, "page_ver_criatura"));
			
		// estilo css para las paginas indicadas
			add_action( 'admin_print_styles-' . $page_anadir_criatura, array( __CLASS__, "estilo_css" ) );
			add_action( 'admin_print_styles-' . $page, array( __CLASS__, "style" ) );
			
			$page20 = add_submenu_page( $parent_slug, self::$submenu_20_title, self::$submenu_20_page_title, self::$all, self::$submenu_20_slug, array(__CLASS__, "menu_sub"));

/*ESTILO:	add_action( 'admin_print_styles-' . $page, array( __CLASS__, "menu_styles" ) );
			add_action( 'admin_print_styles-' . $page_anadir_criatura, array( __CLASS__, "menu_styles" ) );
			add_action( 'admin_print_styles-' . $page3, array( __CLASS__, "menu_styles" ) );*/
		}
		
		function style(){
			wp_enqueue_style( self::$slug_stylesheet, plugins_url(__CLASS__).'/'.self::$name_stylesheet );
		}
		
		function estilo_css(){
			wp_enqueue_style( self::$slug_stylesheet, plugins_url(__CLASS__).'/css/style.css'.self::$css );
		}
		/*
		* function nav
		*
		* @param string $title 	-> texto para indicar el titulo del nav
		* @param array $menu 	-> array doble para indicar el los enlaces. Valores:
		*							* string $slug 		->	slug del submenu
		*							* string $submenu 	->	nombre del submenu
		*							* bolean $who_look 	->	boleano 0 = el submenu lo ven todos los usuarios regisrados (por defecto)
		*																boleano 1 = el submenu solo lo ve los admins
		* @param string $title 	-> (opcional) texto para indicar la descpripcion del nav
		* @param bolean $look	-> (opcional) 	bloeano 0 = el nav lo ven todos los usuarios regisrados (por defecto)
		*										boleano 1 = el nav solo lo ve los admins
		* @return mixed
		*/
		function nav($title, $submenu = array(array('SIN-SLUG','SIN NOMBRE', 0)), $description = '', $look = 0) {
			$n = count($submenu);
			/*echo plugins_url(__CLASS__).'<br>';
			echo plugin_dir_url(__CLASS__).'<br>';
			echo plugin_dir_path(__CLASS__).'<br>';
			echo plugin_basename(__CLASS__).'<br>';*/
			if ($look == 0) {
				echo '<div id="welcome-panel" class="welcome-panel">';
				echo '<div class="welcome-panel-content">';
				echo '<h3>'.$title.'</h3>';
				echo '<p class="about-description">'.$description.'</p>';
				for($i=0; $i<$n; $i++) {
					if ($submenu[$i][2] == 1) { $who_look = self::$only_admins; } else { $who_look = self::$all; }
					if ( current_user_can($who_look) != NULL ) {
						echo '<a class="button button-primary button-hero hide-if-customize" href="?page='.$submenu[$i][0].'">'.$submenu[$i][1].'</a>&nbsp;';
					}
				}
				echo '</div></div><p>&nbsp;</p>';
			}
		}
        
		function page_principal(){
			echo '<div class="wrap">';
			echo self::$icon.'<h2>'.self::$page_principal.'</h2>';
		
		// nav de Clasificador de Criaturas
			$title_criaturas = "Clasificador de Criaturas";
			$description_criaturas = "Herramientas para administrar las criaturas";
			$submenu_criaturas = array(	array( self::$slug_anadir_criatura, self::$title_anadir_criatura, 0 ),
										array( self::$slug_modificar_criatura, self::$title_modificar_criatura, 1),
										array( self::$slug_ver_criatura, self::$title_ver_criatura, 0) );
			self::nav($title_criaturas, $submenu_criaturas, $description_criaturas);
			
			echo '</div>';
		}

        function page_anadir_criatura() {
			echo '<div class="wrap">';
			
			echo self::$icon.'<h2>'.self::$page_principal.' - Clasificador de Criaturas</h2>';
			
			echo '<h3>'.self::$title_anadir_criatura.'</h3>';
			
			include(dirname(__FILE__).'/class/clasificadorCriaturas.php');
			
			$rol_admin = current_user_can('administrator'); // TRUE es admin ; FALSE no es admin

			if ($_POST == NULL) {
				
				$anadirCriatura = new clasificadorCriaturas('anadir', get_current_user_id(), $rol_admin, self::$slug_anadir_criatura, TRUE);
			
			} else {
				
				$anadirCriatura = new clasificadorCriaturas('anadir', get_current_user_id(), $rol_admin, self::$slug_anadir_criatura, FALSE);
				
				// recogida de las variables + limpiarlas de codigo malicioso
				if( isset($_POST["nombre"]) ) { 		$nombre 		= esc_html( sanitize_user( wp_trim_words($_POST["nombre"], 1), true ) ); }
				if( isset($_POST["altura"]) ) { 		$altura 		= number_format( (double) $_POST["altura"], 2 ); }
				if( isset($_POST["ancho"]) ) { 			$ancho 			= number_format( (double) $_POST["ancho"], 2 ); }
				if( isset($_POST["peso"]) ) { 			$peso 			= number_format( (double) $_POST["peso"], 2 ); }
				if( isset($_POST["tipo"]) ) { 			$tipo 			= esc_html( $_POST["tipo"] ); }
				if( isset($_POST["genero"]) ) { 		$genero 		= esc_html( $_POST["genero"] ); }
				if( isset($_POST["habitat"]) ) { 		$habitat 		= sanitize_text_field( $_POST["habitat"] ); }
				if( isset($_POST["descripcion"]) ) { 	$descripcion 	= esc_textarea( $_POST["descripcion"] ); }
				if( isset($_POST["comentario"]) ) { 	$comentario 	= esc_textarea( $_POST["comentario"] ); }
				if( isset($_POST["habilidad1"]) ) { 	$habilidad1 	= esc_html( $_POST["habilidad1"] ); }
				if( isset($_POST["habilidad2"]) ) { 	$habilidad2 	= esc_html( $_POST["habilidad2"] ); }
				if( isset($_POST["habilidad3"]) ) { 	$habilidad3 	= esc_html( $_POST["habilidad3"] ); }
				if( isset($_POST["habilidad4"]) ) { 	$habilidad4 	= esc_html( $_POST["habilidad4"] ); }
				if( isset($_POST["habilidad5"]) ) { 	$habilidad5 	= esc_html( $_POST["habilidad5"] ); }
				//if( isset($_FILES["imgBoceto"]) ) { 	$imgBoceto 		= sanitize_file_name( remove_accents($_POST["imgBoceto" ]) ); }
				//if( isset($_FILES["imgModelado"]) ) { 	$imgModelado 	= sanitize_file_name( remove_accents($_POST["imgModelado"] ) ); }
				//if( isset($_FILES["imgTexturizado"]) ) { $imgTexturizado = sanitize_file_name( remove_accents($_POST["imgTexturizado"] ) ); }
				//if( isset($_FILES["zip"]) ) { 			$zip 			= sanitize_file_name( remove_accents($_POST["zip"] ) ); }
				@$licencia	= trim($_POST["licencia"]);
				if( isset($_POST["filalizado"]) ) { 	$filalizado 	= esc_html( $_POST["filalizado"] );	 }
				
				// form
				$anadirCriatura -> form_criaturas(self::$slug_anadir_criatura, FALSE, $rol_admin, $nombre, $altura, $ancho, $peso, $tipo, $genero, $habitat, $habilidad1, $habilidad2, $habilidad3, $habilidad4, $habilidad5, $descripcion, $licencia);
				
			}
			echo '</div>';
		}
		
		function page_modificar_criatura() {
			echo '<div class="wrap">';
			echo self::$icon.'<h2>'.self::$page_principal.' - '.self::$title_modificar_criatura.'</h2>';
			echo '<h3>'.$submenu.'</h3>';
			self::form_criaturas(self::$slug_modificar_criatura);
			echo '</div>';
		}
		
		function menu_sub($submenu){
			echo '<div class="wrap">';
			echo '<h2>'.self::$page_principal.'</h2>';
			echo '<h3>'.$submenu.'</h3>';
			include( "information.php" );
			echo '</div>';
		}

		
		/* CHECK POST
		function check_post(){
			if( isset( $_REQUEST[self::$post_prefix] ) ){
				$expected = array(
					"opt"=>array(),
					"action"=>"",
					"action_code" => "",
					"verification"=>"");
				$outcome = array_merge($expected, $_REQUEST[self::$post_prefix]);
				extract($outcome);
				if( wp_verify_nonce( $action_code, $action) ){
					if($action === "update_plugin_options"){
						$options = self::option_get();
						foreach($opt as $key=>$value){
							if((int)$value === 1 || (int)$value === 0){
								$options[$key] = (int)$value;
							}
						}
						$res = self::option_set($options);
						if($res === true || $res === NULL){
							self::display_message("Plugin Options Updated");
						}
						else{
							self::display_message("Could Not Update Options, they may not have changed!", false);
						}
					}
					elseif( $action ==="snippet_add" ){
						$opt["snippet_title"] = esc_html($opt["snippet_title"]);
						$id = self::snippet_add( array( "name"=>$opt["snippet_title"], "function"=>$opt["snippet_code"] ) );
						if( $id > 0){
							self::display_message ("Code Snippet Added, you can use this snippet using the shortcode <code>[php function={$id}]</code>");	
						}
						else{
							self::display_message ("Oh dear, could not add the code snippet", false);	
						}
					}
					elseif ($action ==="snippet_edit"){
						if( wp_verify_nonce( $verification, $action.$opt["snippet_id"] ) ){
							$opt["snippet_title"] = esc_html($opt["snippet_title"]);
							$id = self::snippet_edit( $opt["snippet_id"],  array( "name"=>$opt["snippet_title"], "function"=>$opt["snippet_code"] ) );
							if( $id > 0){
								self::display_message ("Code snippet has been updated");	
							}
							else{
								self::display_message ("Oh dear, could not update that code snippet", false);	
							}
						}
					}
					elseif ($action === "snippet_delete"){
						if( wp_verify_nonce( $verification, $action.$opt["snippet_id"] ) ){
							self::snippet_delete( $opt["snippet_id"] );
							self::display_message ("Code snippet has been deleted");	
						}
					}
				}
				else{
					self::display_message( "An error occured, please try again", false );
				}
			}			
		}*/
		
		function display_message( $message="", $good = true){
			$clas = "updated";
			if( $good === false){$clas='error';}
			echo '<div class="'.$clas.' settings-error" id="setting-error-settings_updated"><p><strong>'.$message.'</strong></p></div>';
		}
		
		function snippet_add(  $snippet = array( "name" => "", "function"=>"" ) ){
			global $wpdb;
			if( $wpdb->insert( $wpdb->prefix.self::$database_prefix, $snippet, array("%s", "%s") ) ){
				return $wpdb->insert_id;
			}
			else{
				return 0;	
			}
		}
		function snippet_edit( $id = 0, $snippet = array( "name" => "", "function"=>"" ) ){
			global $wpdb;
			return $wpdb->update( $wpdb->prefix.self::$database_prefix, $snippet, array( "id" => $id ), array("%s", "%s"), array("%d") );
		}
		function snippet_delete( $snippet_id = 0){
			global $wpdb;
			return $wpdb->get_results( $wpdb->prepare( "DELETE FROM `".$wpdb->prefix.self::$database_prefix."` WHERE `id` = %d LIMIT 1", $snippet_id ) );	
		}
		function snippet_get( $snippet_id = 0 ){
			global $wpdb;
			$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `".$wpdb->prefix.self::$database_prefix."` WHERE `id` = %d", $snippet_id ) );	
			if(sizeof($row) > 0){
				$row->function = htmlspecialchars_decode($row->function);
			}
			return $row;
		}
		/* COMENTARIO # No hace falta, pero es buen ejemplo para hacer 'un select de BD'
		 function snippet_get_all( ){
			global $wpdb;
			$rows = $wpdb->get_results( "SELECT * FROM `".$wpdb->prefix.self::$database_prefix."`" );	
			return $rows;
		}*/
		function snippet_swap( $snippet_id = 0){
			$snippet = self::snippet_get($snippet_id);
			if(sizeof($snippet) == 0){
				echo self::snippet_404();
			}
			else{
				eval( stripslashes($snippet->function));
			}
		}
		function snippet_404(){
			$option = self::option_get();
			if( $option["show404"] == 1 ){
				if( is_int( $option["fourohfourmsg"] ) && $option["fourohfourmsg"] !== 0 ){
					$snippet = self::snippet_get( $option["fourohfourmsg"] );
					return $snippet->function;
				}
				else{
					return "<span style='font-weight:bold; color:red'>Function does not exist</span>";;
				}
			}
			return "";
		}
		
		/* COMENTARIO # Buen ejemplo de aad
		function form_add_snippet(){
			?>	
			<form action="?page=<?php echo self::$menu_slug?>" method="post">
				<input type="hidden" name="<?php echo self::$post_prefix?>[action]" value="snippet_add" />
				<?php wp_nonce_field( "snippet_add", self::$post_prefix."[action_code]");?>
				<p>
					<label for="<?php echo self::$post_prefix?>[opt][snippet_title]">Snippet Title</label><input type="text" class="form-field" name="<?php echo self::$post_prefix?>[opt][snippet_title]" required placeholder="Snippet Title, e.g. My First Snippet" />
				</p>
				<p>
					<label for="<?php echo self::$post_prefix?>[opt][snippet_code]">Snippet Code<br /><br /><em>all snippets automatically start with &lt;?php</em></label>
					<textarea name="<?php echo self::$post_prefix?>[opt][snippet_code]" required class="code-field" placeholder="Your Code Snippet"></textarea>
				</p>
				<input type="submit" value="Save Snippet" />
			</form>
			<?php
		}
		*/
		function form_edit_snippet( $snippet ){
			?>	
			<form action="?page=<?php echo self::$menu_1_slug?>" method="post">
				<input type="hidden" name="<?php echo self::$post_prefix?>[action]" value="snippet_edit" />
				<?php wp_nonce_field( "snippet_edit", self::$post_prefix."[action_code]");?>
				<?php wp_nonce_field( "snippet_edit".$snippet->id, self::$post_prefix."[verification]");?>
				<input type="hidden" name="<?php echo self::$post_prefix?>[opt][snippet_id]" value="<?php echo esc_attr($snippet->id)?>" />
				<p>
					<label for="<?php echo self::$post_prefix?>[opt][snippet_title]">Snippet Title</label><input type="text" class="form-field" name="<?php echo self::$post_prefix?>[opt][snippet_title]" required placeholder="Snippet Title, e.g. My First Snippet" value='<?php echo esc_attr(stripslashes($snippet->name))?>' />
				</p>
				<p>
					<label for="<?php echo self::$post_prefix?>[opt][snippet_code]">Snippet Code<br /><br /><em>all snippets automatically start with &lt;?php</em></label>
					<textarea name="<?php echo self::$post_prefix?>[opt][snippet_code]" required class="code-field" placeholder="Your Code Snippet"><?php echo esc_html(stripslashes($snippet->function))?></textarea>
				</p>
					<input type="submit" value="Update Snippet" /> <a href="?page=<?php echo self::$menu_1_slug?>&<?php echo self::$post_prefix?>[action]=snippet_delete&<?php echo self::$post_prefix?>[action_code]=<?php echo wp_create_nonce( "snippet_delete")?>&<?php echo self::$post_prefix?>[opt][snippet_id]=<?php echo $snippet->id?>&<?php echo self::$post_prefix?>[verification]=<?php echo wp_create_nonce( "snippet_delete".$snippet->id)?>" class='delete-button' onclick="return confirm('Are you sure you want to delete this snippet?')">Delete This Snippet</a>
			</form>
			<?php	
		}
		/** COMENTARIO # Utilizada y fin!!!
		 *function form_general_options($option){
			extract($option);
			?>
			<form action="?page=<?php echo self::$menu_slug;?>" method="post">
				<input type="hidden" name="<?php echo self::$post_prefix?>[action]" value="update_plugin_options" />
				<?php wp_nonce_field( "update_plugin_options", self::$post_prefix."[action_code]");?>
				<input type="hidden" value="0" name="<?php echo self::$post_prefix?>[opt][fourohfourmsg]" />
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="<?php echo self::$post_prefix?>[opt][show404]"><strong>Show the snippet not found message?</strong></label>
							</th>
							<td>
								Yes: <input type="radio" name="<?php echo self::$post_prefix?>[opt][show404]" <?php checked($show404, 1, true);?> value="1" /><br />No: <input type="radio" name="<?php echo self::$post_prefix?>[opt][show404]" <?php checked($show404, 0, true);?> value="0" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="<?php echo self::$post_prefix?>[opt][preparse]"><strong>Use the old (pre 2.2) code replacement method</strong><br /><em>Not Recommended</em></label>
							</th>
							<td>
								Yes: <input type="radio" name="<?php echo self::$post_prefix?>[opt][preparse]" <?php checked($preparse, 1, true);?> value="1" /><br />No: <input type="radio" name="<?php echo self::$post_prefix?>[opt][preparse]" <?php checked($preparse, 0, true);?> value="0" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="<?php echo self::$post_prefix?>[opt][use_advanced_filter]"><strong>Use the advanced filter method</strong><br /><em>Removes the code replacement method</em></label>
							</th>
							<td>
								Yes: <input type="radio" name="<?php echo self::$post_prefix?>[opt][use_advanced_filter]" <?php checked($use_advanced_filter, 1, true);?> value="1" /><br />No: <input type="radio" name="<?php echo self::$post_prefix?>[opt][use_advanced_filter]" <?php checked($use_advanced_filter, 0, true);?> value="0" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="<?php echo self::$post_prefix?>[opt][total_uninstall]"><strong>Remove all plugin data on uninstall</strong><br /><em>only applies when the plugin is deleted via the plugins menu</em></label>
							</th>
							<td>
								Yes: <input type="radio" name="<?php echo self::$post_prefix?>[opt][total_uninstall]" <?php checked($total_uninstall, 1, true);?> value="1" /><br />No: <input type="radio" name="<?php echo self::$post_prefix?>[opt][total_uninstall]" <?php checked($total_uninstall, 0, true);?> value="0" />
							</td>
						</tr>
					</tbody>
				</table>
				<p><input type="submit" class="button-primary" value="Update Options" /></p>
			</form>	
			<?php
		}*/
		//SERVIDDA!
		function option_get(){
			$defaults = array(
					"show404" => 0,
					"fourohfourmsg" => 0, 
					"dbVersion" => 0,
					"use_advanced_filter" => 0,
					"preparse" => 0,
					"total_uninstall" => 0,
				);
			$options = get_option(self::$option_name,$defaults);
			return array_merge($defaults, $options);
		}
		function option_set( $new_options = array() ){
			return update_option( self::$option_name, $new_options);
		}

		function shortcode($args, $content=""){
			$option = self::option_get();
			$default_args = array('debug' => 0,'silentdebug' => 0, 'function' => 0, 'mode'=>'new');
			extract( shortcode_atts( $default_args, $args));
			$four0four_used = false;
			//Debug settings
			if($debug == 1){
				error_reporting(E_ALL);
				ini_set("display_errors","1");
			}
			
			if($function == 0):
				if( $mode == "new" || ($option["preparse"] == 0 && $mode == "new") ){
					$content = strip_tags($content);
					$content = preg_replace("/\[{1}([\/]*)([a-zA-z\/]{1}[a-zA-Z0-9]*[^\'\"])([a-zA-Z0-9 \!\"\£\$\%\^\&\*\*\(\)\_\-\+\=\|\\\,\.\/\?\:\;\@\'\#\~\{\}\¬\¦\`\<\>]*)([\/]*)([\]]{1})/ix","<$1$2$3>",$content,"-1");
					$content = htmlspecialchars($content, ENT_NOQUOTES);
					$content = str_replace("&amp;#8217;","'",$content);
					$content = str_replace("&amp;#8216;","'",$content);
					$content = str_replace("&amp;#8242;","'",$content);
					$content = str_replace("&amp;#8220;","\"",$content);
					$content = str_replace("&amp;#8221;","\"",$content);
					$content = str_replace("&amp;#8243;","\"",$content);
					$content = str_replace("&amp;#039;","'",$content);
					$content = str_replace("&#039;","'",$content);
					$content = str_replace("&amp;#038;","&",$content);
					$content = str_replace("&amp;gt;",'>',$content);
					$content = str_replace("&amp;lt;",'<',$content);
					$content = htmlspecialchars_decode($content);
				}
				else{
					$content =(htmlspecialchars($content,ENT_QUOTES));
					$content = str_replace("&amp;#8217;","'",$content);
					$content = str_replace("&amp;#8216;","'",$content);
					$content = str_replace("&amp;#8242;","'",$content);
					$content = str_replace("&amp;#8220;","\"",$content);
					$content = str_replace("&amp;#8221;","\"",$content);
					$content = str_replace("&amp;#8243;","\"",$content);
					$content = str_replace("&amp;#039;","'",$content);
					$content = str_replace("&#039;","'",$content);
					$content = str_replace("&amp;#038;","&",$content);
					$content = str_replace("&amp;lt;br /&amp;gt;"," ", $content);
					$content = htmlspecialchars_decode($content);
					$content = str_replace("<br />"," ",$content);
					$content = str_replace("<p>"," ",$content);
					$content = str_replace("</p>"," ",$content);
					$content = str_replace("[br/]","<br/>",$content);
					$content = str_replace("\\[","&#91;",$content);
					$content = str_replace("\\]","&#93;",$content);
					$content = str_replace("[","<",$content);
					$content = str_replace("]",">",$content);
					$content = str_replace("&#91;",'[',$content);
					$content = str_replace("&#93;",']',$content);
					$content = str_replace("&gt;",'>',$content);
					$content = str_replace("&lt;",'<',$content);
				}
			else:
				//function selected
				$snippet = self::snippet_get($function);
				if( sizeof( $snippet ) == 0){
					$four0four_used = true;
					$content = self::snippet_404();
				}
				else{
					$content = stripslashes($snippet->function);
				}
			endif;
			ob_start();
			eval($content);
			if($debug == 1||$silentdebug == 1){
				if($silentdebug == 1){
					echo "\n\n<!-- ALLOW PHP SILENT DEBUG MODE - - > \n\n\n";
				}else{
					echo "<p align='center'>Allow PHP Debug</p>";
				}
				if($four0four_used){
					$content = "Function id : $function : cannot be found<br/>";
				}else{
					$content =(htmlspecialchars($content,ENT_QUOTES));
				}
				echo "<pre>".$content."</pre>";
				if($silentdebug == 1){
					echo "\n\n\n<- - END ALLOW PHP SILENT DEBUG MODE -->\n\n";
				}else{
					echo "<p align='center'>End Allow PHP Debug</p>";
				}
			}
			return ob_get_clean();			
		}
		
		function shortcode_advanced($args){
			$options = self::option_get();
			if( isset( $options['use_advanced_filter'] ) ){
				
				if( $options['use_advanced_filter'] == "1" ){
					remove_shortcode("php");
					remove_shortcode("PHP");
					remove_shortcode("allowphp");
					remove_shortcode("ALLOWPHP");
					
					$args = str_ireplace("[php]","<?php ",$args);
					$args = str_ireplace("[/php]"," ?>",$args);
					
					$args = str_ireplace("[php useadvancedfilter]","<?php ",$args);
					$args = str_ireplace("[/php useadvancedfilter]"," ?>",$args);
					
					$args = str_ireplace("[allowphp]","<?php ",$args);
					$args = str_ireplace("[/allowphp]"," ?>",$args);
					
					$args = str_ireplace("[allowphp useadvancedfilter]","<?php ",$args);
					$args = str_ireplace("[/allowphp useadvancedfilter]"," ?>",$args);
					
					$args = preg_replace( "#\[php(.*?)function=([0-9]*)(.*?)\]#", "<?php allow_php_in_posts::snippet_swap( $2 ) ?>",$args);
					$args = preg_replace( "#\[allowphp(.*?)function=([0-9]*)(.*?)\]#", "<?php allow_php_in_posts::snippet_swap( $2 ) ?>",$args);
					ob_start();
					eval("?>".$args);
					$return = ob_get_clean();
					return $return;
				}
				else{
					return $args;	
				}
				
			}
			$args = str_ireplace("[php useadvancedfilter]","<?php ",$args);
			$args = str_ireplace("[/php useadvancedfilter]"," ?>",$args);
			
			$args = str_ireplace("[allowphp useadvancedfilter]","<?php ",$args);
			$args = str_ireplace("[/allowphp useadvancedfilter]"," ?>",$args);
			
			ob_start();
			eval("?>".$args);
			$returned = ob_get_clean();
			return $returned;	
		}
		
		function hook_activation(){
			self::db_check();	
		}
		function hook_uninstall(){
			$option = self::option_get();
			if($option["total_uninstall"] === 1){
				global $wpdb;
				$wpdb->query("DROP TABLE `".$wpdb->prefix.self::$database_prefix."`");
				delete_option( self::$option_name );
			}
		}
		
		function db_check(){
			$opt = self::option_get();
			if($opt["dbVersion"] != self::$database_version){
				self::db_upgrade();
			}
		}
		function db_upgrade(){
			global $wpdb;
			$sql = "RENAME TABLE `".$wpdb->prefix."allowPHP_functions` TO `".$wpdb->prefix.self::$database_prefix."`";
			$wpdb->get_results($sql);
			$sql = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix.self::$database_prefix."(
				id int NOT NULL AUTO_INCREMENT,
				name varchar(100) NOT NULL,
				function longtext NOT NULL,
				PRIMARY KEY(id)
			);";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			//need to manually change existing function columns
			$wpdb->get_results("ALTER TABLE `".$wpdb->prefix.self::$database_prefix."` CHANGE `function` `function` LONGTEXT NOT NULL ");
			$opt = self::option_get();
			$opt["dbVersion"] = self::$database_version;
			self::option_set($opt);
		}
	}
	
	function inimat_init(){
		global $inimat;
		$inimat = new inimat();	
	}
	add_action("init","inimat_init");
	register_activation_hook( __FILE__ , array( "allow_php_in_posts" , "hook_activation" ) );
	register_uninstall_hook( __FILE__, array( "allow_php_in_posts", "hook_uninstall" ) );
}
?>