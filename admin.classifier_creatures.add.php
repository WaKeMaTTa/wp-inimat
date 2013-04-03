<?php
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}


// Includes
require_once( WPINIMAT_PLUGIN_PATH . 'class/zebra_form/Zebra_Form.php' );
require_once( WPINIMAT_PLUGIN_PATH . 'class/Zebra_Image.php' );
require_once( WPINIMAT_PLUGIN_PATH . 'class/Inimat_Functions.php' );

$functions = new Inimat_Functions();

// var globlas
global $wpdb, $current_user;

// instantiate a Zebra_Form object
$form = new Zebra_Form('form');

// Language for errors form
if (WPLANG == 'es_ES') {
	$form->language('espanol');
}

## Author

if(current_user_can('manage_options')) {
	
	$form->add('label', 'label_author', 'author', __('Author:', 'wpinimat_languages'));
	
	$obj = $form->add('select', 'author', $current_user->ID-1, array('other' => true, 'style' => 'height: 28px;'));
	
	$users = $wpdb->get_results ("SELECT ID, display_name FROM ".$wpdb->users." ORDER BY ID ASC", ARRAY_A );
	
	$array_asosiativo = array();
	
	for($i=0; $i<$wpdb->num_rows; $i++) {
		$array_asosiativo[$i] = $users[$i]["display_name"];
	}
	
	$obj->add_options($array_asosiativo);
	$obj->set_rule(array(
	
		'required' => array('error', 'Author is required!')
		
	));
	
}

## Name

	$form->add('label', 'label_name', 'name', __('Name creature:', 'wpinimat_languages'));
	
	$obj = $form->add('text', 'name');
	
	$obj->set_rule(array(
	
		'required'	=>  array('error', __('Name of the creature is required!', 'wpinimat_languages')),
		'alphabet'	=>  array('error', __('Accepts only characters from the alphabet', 'wpinimat_languages')),
	
	));
	
	$obj->change_case('lower');
	
	$form->add('note', 'note_name', 'name', __('Accepts only characters from the alphabet', 'wpinimat_languages'));

## Height

	$form->add('label', 'label_height', 'height', __('Height:', 'wpinimat_languages'));
	
	$obj = $form->add('text', 'height');
	
	$obj->set_rule(array(
	
		'required'	=>  array('error', __('Height of the creature is required!', 'wpinimat_languages')),
		'float'		=>  array('', 'error', __('Accepts only digits (0 to 9) and/or one dot (.)', 'wpinimat_languages')),
	
	));
	
	$form->add('note', 'note_height', 'height', __('Accepts only digits (0 to 9) and/or one dot (.)', 'wpinimat_languages'));
	
## Width

	$form->add('label', 'label_width', 'width', __('Width:', 'wpinimat_languages'));
	
	$obj = $form->add('text', 'width');
	
	$obj->set_rule(array(
	
		'required'	=>  array('error', __('Width of the creature is required!', 'wpinimat_languages')),
		'float'		=>  array('', 'error', __('Accepts only digits (0 to 9) and/or one dot (.)', 'wpinimat_languages')),
	
	));
	
	$form->add('note', 'note_width', 'width', __('Accepts only digits (0 to 9) and/or one dot (.)', 'wpinimat_languages'));
	
## Weight

	$form->add('label', 'label_weight', 'weight', __('Weight:', 'wpinimat_languages'));
	
	$obj = $form->add('text', 'weight');
	
	$obj->set_rule(array(
	
		'required'	=>  array('error', __('Weight of the creature is required!', 'wpinimat_languages')),
		'float'		=>  array('', 'error', __('Accepts only digits (0 to 9) and/or one dot (.)', 'wpinimat_languages')),
	
	));
	
	$form->add('note', 'note_weight', 'weight', __('Accepts only digits (0 to 9) and/or one dot (.)', 'wpinimat_languages'));
	
## Type

	$form->add('label', 'label_type', 'type', __('Type:', 'wpinimat_languages'));
	
	$obj = $form->add('radios', 'type', array(
	
		'astral'	=>  __('Astral', 'wpinimat_languages'),
		'guardian'	=>  __('Guardian', 'wpinimat_languages'),
		'material'	=>  __('Material', 'wpinimat_languages'),
		'samus'		=>  __('Samus', 'wpinimat_languages'),
		
	));
	
	$obj->set_rule(array(
	
		'required' => array('error', __('Type creature selection is required!', 'wpinimat_languages'))
	
	));

## Gender

	$form->add('label', 'label_gender', 'gender', __('Gender:', 'wpinimat_languages'));
	
	$obj = $form->add('radios', 'gender', array(
	
		'aquatic'		=>  __('Aquatic', 'wpinimat_languages'),
		'terrestrial' 	=>  __('Terrestrial', 'wpinimat_languages'),
		'vegetable'		=>  __('Vegetable', 'wpinimat_languages'),
		'flying'		=>  __('Flying', 'wpinimat_languages'),
		
	));
	
	$obj->set_rule(array(
	
		'required' => array('error', __('Type creature selection is required!', 'wpinimat_languages'))
	
	));
	
## Skills
		
	$form->add('label', 'label_skills', 'skills', __('Skills:', 'wpinimat_languages'));
	
	$form->add('label', 'label_skills', 'skills', __('Skills:', 'wpinimat_languages'));
	
	$obj = $form->add('text', 'skill_1', '', array('style' => 'display: inline; margin-bottom: 5px;'));
	
	$obj->change_case('lower');
	
	$obj->set_rule(array(
	
		'required' => array('error', __('Skill selection is required!', 'wpinimat_languages'))
	
	));
	
	$obj = $form->add('select', 'type_skill_1', '',  array('style' => 'height: 28px; display: inline; margin-bottom: 5px;'));
	
	$obj->add_options(array(
	
		''	=>  __('- SELECT -', 'wpinimat_languages'),	
		'attack'	=>  __('Attack', 'wpinimat_languages'),
		'defense'	=>  __('Defense', 'wpinimat_languages'),
		
	));
	
	$obj->set_rule(array(
	
		'required' => array('error', __('Type skill selection is required!', 'wpinimat_languages'))
	
	));
	
	$obj = $form->add('textarea', 'desc_skill_1');
	
	$obj->change_case('lower');
	
## Habitat

	$form->add('label', 'label_habitat', 'habitat', __('Habitat:', 'wpinimat_languages'));
	
	$obj = $form->add('textarea', 'habitat');
	
	$obj->set_rule(array(
	
		'required' => array('error', __('Habitat is required!', 'wpinimat_languages'))
	
	));
	
	$obj->change_case('lower');
	
## Description

	$form->add('label', 'label_description', 'description', __('Description:', 'wpinimat_languages'));
	
	$obj = $form->add('textarea', 'description', '', array('style' => 'height: 250px;'));
	
	$obj->set_rule(array(
	
		'required' => array('error', __('Description is required!', 'wpinimat_languages'))
	
	));
	
	$obj->change_case('lower');
	
## Imagen Sketch

	$form->add('label', 'label_imgSketch', 'imgSketch', __('Imagen Sketch:', 'wpinimat_languages'));
	
	$obj = $form->add('file', 'imgSketch');
	
	$obj->set_rule(array(
	
		// 'required'  =>  array('error', __('An image is required!', 'wpinimat_languages')),
        'upload'    =>  array($functions->path_relative().'upload', ZEBRA_FORM_UPLOAD_RANDOM_NAMES, 'error', __('Could not upload file!<br>Check that the "creatureSs" folder exists and that it is writable', 'wpinimat_languages')),
        'image'  =>  array('error', __('File must be a jpg, png or gif image!', 'wpinimat_languages')),
        'filesize'  =>  array(1048576, 'error', __('File size must not exceed 1 MB!', 'wpinimat_languages')),
	
	));
	
    $form->add('note', 'note_imgSketch', 'imgSketch', __('File must have the .jpg, .jpeg, png or .gif extension, and no more than 1 MB!', 'wpinimat_languages'));
	
## Imagen Modeled

	$form->add('label', 'label_imgModeled', 'imgModeled', __('Imagen Modeled:', 'wpinimat_languages'));
	
	$obj = $form->add('file', 'imgModeled');
	
	$obj->set_rule(array(
	
		// 'required'  =>  array('error', __('An image is required!', 'wpinimat_languages')),
        'upload'    =>  array($functions->path_relative().'upload', ZEBRA_FORM_UPLOAD_RANDOM_NAMES, 'error', __('Could not upload file!<br>Check that the "creaturesMMM" folder exists and that it is writable', 'wpinimat_languages')),
        'image'  =>  array('error', __('File must be a jpg, png or gif image!', 'wpinimat_languages')),
        'filesize'  =>  array(1048576, 'error', __('File size must not exceed 1 MB!', 'wpinimat_languages')),
	
	));
	
	$form->add('note', 'note_imgModeled', 'imgModeled', __('File must have the .jpg, .jpeg, png or .gif extension, and no more than 1 MB!', 'wpinimat_languages'));
	
## Imagen Textured

	$form->add('label', 'label_imgTextured', 'imgTextured', __('Imagen Textured:', 'wpinimat_languages'));
	
	$obj = $form->add('file', 'imgTextured');
	
	$obj->set_rule(array(
	
		// 'required'  =>  array('error', __('An image is required!', 'wpinimat_languages')),
        'upload'    =>  array($functions->path_relative().'upload', ZEBRA_FORM_UPLOAD_RANDOM_NAMES, 'error', __('Could not upload file!<br>Check that the "creatures" folder exists and that it is writable', 'wpinimat_languages')),
        'image'  =>  array('error', __('File must be a jpg, png or gif image!', 'wpinimat_languages')),
        'filesize'  =>  array(1048576, 'error', __('File size must not exceed 1 MB!', 'wpinimat_languages')),
	
	));
	
	$form->add('note', 'note_imgTextured', 'imgTextured', __('File must have the .jpg, .jpeg, png or .gif extension, and no more than 1 MB!', 'wpinimat_languages'));

## File

	$form->add('label', 'label_file', 'file', __('File blender (zipped):', 'wpinimat_languages'));
	
	$obj = $form->add('file', 'file');
	
	$obj->set_rule(array(
	
		// 'required'  =>  array('error', __('An image is required!', 'wpinimat_languages')),
		'upload'    =>  array($functions->path_relative().'upload', ZEBRA_FORM_UPLOAD_RANDOM_NAMES, 'error', __('Could not upload file!<br>Check that the "upload" folder exists inside and that it is writable', 'wpinimat_languages')),
		'filetype'  =>  array('zip, tgz, rar, bzip', 'error', __('File must be a zip, rar or tgz!', 'wpinimat_languages')),
		'filesize'  =>  array(10485760, 'error', __('File size must not exceed 10 MB!', 'wpinimat_languages')),
	
	));
	
	$form->add('note', 'note_file', 'file', __('File must have the .zip extension, and no more than 10 MB!', 'wpinimat_languages'));

## License

    $obj = $form->add('checkbox', 'license', 1,  array('style' => 'float: left; margin-right: 6px;'));
	
    $form->add('label', 'label_license_1', 'license_1', __('I accept the license ', 'wpinimat_languages') . '<a href="http://creativecommons.org/licenses/by-sa/3.0" target="_blank">Creative Commons By-SA 3.0</a>', array('style' => 'font-weight:normal'));
	
	$obj->set_rule(array(
	
		'required' => array('error', 'Accept the license is required!')
	
	));
	
## Finished

if(current_user_can('manage_options')) {
	
	 $obj = $form->add('checkbox', 'finished', 1,  array('style' => 'float: left; margin-right: 6px;'));
	
    $form->add('label', 'label_finished_1', 'finished_1', __('Check the box if the creature is 100% completed', 'wpinimat_languages'), array('style' => 'font-weight:normal'));
	
}

## Submit

    $form->add('submit', 'btnsubmit', __('Submit', 'wpinimat_languages'));

// validate the form
if($form->validate()) {
	
	// Verify name the creature not is duplicate
	$query = $wpdb -> query( $wpdb -> prepare("SELECT id FROM ".$wpdb->prefix."inimat_creatures WHERE name = %s", $_POST["name"]) );
	
	if (!$query <= 0) {
		
		$form->add_error('error', __('Name the creature is duplicate, please change name of the creature', 'wpinimat_languages'));
		
	} else {
		
		// Create thumbnail of imgSketch
		$sql_imgSketch = '';
		
		if ( isset($form->file_upload["imgSketch"]) ) {
			
			$image = new Zebra_Image();
			
			$image->source_path = WPINIMAT_PLUGIN_PATH . 'upload/' . $form->file_upload["imgSketch"]["file_name"];
			
			$image->target_path = WPINIMAT_PLUGIN_PATH . 'upload/th/' . $form->file_upload["imgSketch"]["file_name"];
			
			$image->resize(100, 100, ZEBRA_IMAGE_BOXED, -1);
			
			$sql_imgSketch = serialize($form->file_upload["imgSketch"]);
			
		}
		
		// Create thumbnail of imgModeled
		$sql_imgModeled = '';
		
		if ( isset($form->file_upload["imgModeled"]) ) {
			
			$image = new Zebra_Image();
			
			$image->source_path = WPINIMAT_PLUGIN_PATH . 'upload/' . $form->file_upload["imgModeled"]["file_name"];
			
			$image->target_path = WPINIMAT_PLUGIN_PATH . 'upload/th/' . $form->file_upload["imgModeled"]["file_name"];
			
			$image->resize(100, 100, ZEBRA_IMAGE_BOXED, -1);
			
			$sql_imgModeled = serialize($form->file_upload["imgModeled"]);
			
		}
		
		// Create thumbnail of imgTextured
		$sql_imgTextured = '';
		
		if ( isset($form->file_upload["imgTextured"]) ) {
			
			$image = new Zebra_Image();
			
			$image->source_path = WPINIMAT_PLUGIN_PATH . 'upload/' . $form->file_upload["imgTextured"]["file_name"];
			
			$image->target_path = WPINIMAT_PLUGIN_PATH . 'upload/th/' . $form->file_upload["imgTextured"]["file_name"];
			
			$image->resize(100, 100, ZEBRA_IMAGE_BOXED, -1);
			
			$sql_imgTextured = serialize($form->file_upload["imgTextured"]);
			
		}
		
		// File
		$sql_file = '';
		
		if ( isset($form->file_upload["file"]) ) {
			
			$sql_file = serialize($form->file_upload["file"]);
			
		}
		
		if (isset($_POST["author"])) {
			
			// Author in not registred in database
			if ($_POST["author"] == 'other') {
				
				$sql_id_author = 0;
				
				$sql_name_author = $_POST["author_other"];
				
			} else {
				// Author is registred in database
				$sql_id_author = $_POST["author"]+1;
				
				$sql_name_author = '';
				
			}
			
		} else {
			
			$sql_id_author = $current_user->ID;
			
			$sql_name_author = '';
			
		}
		
		// Prepare skills for SQL
		$array_skills = array();
		
		for($i=1; $i<=$_POST["skill_total"]; $i++) {
			
			$array_skills[$i][0] = $_POST["skill_".$i];
			
			$array_skills[$i][1] = $_POST["type_skill_".$i];
			
			$array_skills[$i][2] = $_POST["desc_skill_".$i];
		}
		
		$sql_skills = serialize($array_skills);
		
		// finished
		if(isset($_POST["finished"])) {
			
			$sql_finished = $_POST["finished"];
			
		} else {
			
			$sql_finished = 0;
			
		}
		
		// Upload values to database		
		$query = $wpdb -> query( 
		
			$wpdb -> prepare("
				INSERT INTO ".$wpdb->prefix."inimat_creatures ( 
					date_add, id_author, name_author, name, height, width, weight, type, gender, skills, habitat, description, sketch, modeled, textured, file, license, finished
				) VALUES ( 
					CURRENT_TIMESTAMP, %d, %s, %s, %f, %f, %f, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d 
				)",
				
				$sql_id_author, $sql_name_author, $_POST["name"], $_POST["height"], $_POST["width"], $_POST["weight"], $_POST["type"], $_POST["gender"], $sql_skills, $_POST["habitat"], $_POST["description"], $sql_imgSketch, $sql_imgModeled, $sql_imgTextured, $sql_file, $_POST["license"], $sql_finished
				
			)
			
		);
		
		// Verify if values is upload to database
		if($query === FALSE) {
			
			$form->add_error('error', __('Failed to upload the values to the database', 'wpinimat_languages'));
			
		} else {
			
			$functions->msg(__('Thanks for add creature!', 'wpinimat_languages'), 'correct');
			
		}
		
		// debug mode
		if (WP_DEBUG == TRUE) {
			
			echo '<h3>Debug Mode:</h3>';
			
			print_r('<pre>');
			
			print_r($_POST);
			
			print_r($form->file_upload);
			
			die();
		
		}
	
	}
}
	
// auto generate output, labels above form elements
//$form->render('*horizontal');
$form->render(WPINIMAT_PLUGIN_PATH.'admin.classifier_creatures.add.template.php');

?>