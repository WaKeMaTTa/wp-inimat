<?php
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

global $wpdb;

require_once( WPINIMAT_PLUGIN_PATH . 'class/Inimat_Functions.php' );
require_once( WPINIMAT_PLUGIN_PATH . 'class/Page_Lite.php' );

$functions = new Inimat_Functions();

$num_rows_x_page = 20;

if(isset($_GET['p'])) { $num_page= $_GET['p']; } else { $num_page = 1; }

$previous_rows = ($num_page - 1) * $num_rows_x_page;

// Filter of Type Creature
$filter_type = isset($_GET["type"]) ? $_GET["type"] : '';

switch ($filter_type) {
	case 'material':
		$filter_type = 'material';
		break;
	case 'astral':
		$filter_type = 'astral';
		break;
	case 'guardian':
		$filter_type = 'guardian';
		break;
	case 'samus':
		$filter_type = 'samus';
		break;
	default:
		$filter_type = '';
}

// Filter of Type Creature
$filter_finished = isset($_GET["finished"]) ? $_GET["finished"] : '';

switch ($filter_finished) {
	case 'yes':
		$filter_fini = 1;
		break;
	case 'no':
		$filter_fini = 0;
		break;
	default:
		$filter_fini = '';
}

// Search
$search = isset($_GET["search"]) ? strtolower(trim($_GET["search"])) : '';

// SQL SELECT
$select = 'id, date_add, id_author, name_author, name, description, sketch, modeled, textured, finished';

// SQL
if ( ($filter_type != '') || ($filter_fini != '') ) {
	
	if (($filter_type != '') && ($filter_fini != '')) {
		
		$filters = "WHERE `type` = '" . $filter_type . "' AND `finished` = '" . $filter_fini . "'";
		
	} elseif ($filter_type !== '') {
		
		$filters = " WHERE `type` = '" . $filter_type . "'";
		
	} else {
		
		$filters = "WHERE `finished` = '" . $filter_fini . "'";
		
	}
	
	$sql = $wpdb->get_results("SELECT " . $select . " FROM " . $wpdb->prefix . "inimat_creatures " . $filters . " ORDER BY date_add DESC LIMIT " . $previous_rows . " , " . $num_rows_x_page, ARRAY_A);
	
	$num_total_records = count( $wpdb->get_results("SELECT id FROM " . $wpdb->prefix . "inimat_creatures " . $filters, ARRAY_A) );
	
} elseif ($search != '') {
	
	$sql = $wpdb->get_results("SELECT " . $select . " FROM " . $wpdb->prefix . "inimat_creatures WHERE `name` LIKE '%" . $search . "%' ORDER BY date_add DESC LIMIT " . $previous_rows . " , " . $num_rows_x_page, ARRAY_A);
	
	$num_total_records = count( $wpdb->get_results("SELECT id FROM " . $wpdb->prefix . "inimat_creatures WHERE `name` LIKE '%" . $search . "%'", ARRAY_A) );
	
} else {
	
	$sql = $wpdb->get_results("SELECT " . $select . " FROM " . $wpdb->prefix . "inimat_creatures ORDER BY date_add DESC LIMIT " . $previous_rows . " , " . $num_rows_x_page, ARRAY_A);
	
	$num_total_records = count( $wpdb->get_results("SELECT id FROM " . $wpdb->prefix . "inimat_creatures", ARRAY_A) );
	
}

$url = 'admin.php?page=wpinimat/classifier_creatures';
$url .= isset($_GET["finished"]) ? '&finished=' . $_GET["finished"] : '';
$url .= isset($_GET["type"]) ? '&type=' . $_GET["type"] : '';

$paginator = new Page_Lite($num_total_records, $url);

$paginator->setAutoIdentifier(true);

$paginator->setIdentifier('p');

$paginator->setPerPage($num_rows_x_page);

$paginator->setPageJumpCount(10);

$paginator->setRange(5);

$paginator->setStyleArray(
		array(
			'start' => '<div class="tablenav-pages"><span class="displaying-num">'
						. $num_total_records . __(' elements', 'wpinimat_languages') . ' | ' 
						. $paginator->getCurrentPage() . __(' of ', 'wpinimat_languages') 
						. $paginator->getTotalPages() . '</span>',
			
			'end'   => '</div>',
			'first_start' => '<span class="first-page">',
			'first_end' => '</span>',
			'current_start' => '<a class="disabled">',
			'current_end' => '</a>&nbsp;',
			'next_start' => '<span class="next-page">',
			'next_end' => '</span>',
			'prev_start' => '<span class="prev-page">',
			'prev_end' => '</span>',
			'link_start' => '',
			'link_end' => '',
			'last_start' => '<span class="last-page">',
			'last_end' => '</span>',
		)
	);
	
$paginator->setLinkFirst('&laquo');

$paginator->setLinkLast('&raquo;');

$paginator->setLinkPrev('&lsaquo;');

$paginator->setLinkNext('&rsaquo;');

$paginator->setDisplayLinkNext(true);

$paginator->setDisplayLinkPrev(true);

## HTML

?>

	<form name="form_creature" id="form_creature" action="admin.php" method="GET">
    <input type="hidden" name="page" value="wpinimat/classifier_creatures" />
	
    <div class="tablenav top">
        <div class="alignleft actions">
            <select name="finished">
                <option value=""><?php _e('Show all creatures', 'wpinimat_languages'); ?></option>
                <option value="no" <?php echo ($filter_finished === 'no') ? 'selected="selected" >' : '>'; _e('Only creatures no finished', 'wpinimat_languages'); ?></option>
                <option value="yes" <?php echo ($filter_finished === 'yes') ? 'selected="selected" >' : '>'; _e('Only creatures finished', 'wpinimat_languages'); ?></option>
            </select>
            <input type="submit" id="submit" class="button" value="<?php _e('Filter', 'wpinimat_languages'); ?>">
        </div>
        
        <div class="alignleft actions">
            <select name="type">
                <option value=""><?php _e('Show all creatures', 'wpinimat_languages'); ?></option>
                <option value="material" <?php echo ($filter_type === 'material') ? 'selected="selected" >' : '>'; _e('Material', 'wpinimat_languages'); ?></option>
                <option value="astral" <?php echo ($filter_type === 'astral') ? 'selected="selected" >' : '>'; _e('Astral', 'wpinimat_languages'); ?></option>
                <option value="guardian" <?php echo ($filter_type === 'guardian') ? 'selected="selected" >' : '>'; _e('Guardian', 'wpinimat_languages'); ?></option>
                <option value="samus" <?php echo ($filter_type === 'samus') ? 'selected="selected" >' : '>'; _e('Samus', 'wpinimat_languages'); ?></option>
            </select>
            <input type="submit" id="submit" class="button" value="<?php _e('Filter', 'wpinimat_languages'); ?>">
        </div>
        
        <p class="search-box">
            <input type="text" name="search" id="search">
            <input type="submit" id="post-query-submit" value="<?php _e('Search', 'wpinimat_languages'); ?>" class="button">
        </p>
	</form>
    
	<?php echo $paginator->build(); ?>
    </div>
    
    <table class="wp-list-table widefat fixed posts" cellspacing="0">
    <thead>
        <tr>
            <th scope="col" id="edit" class="manage-column column-cb check-column" style="width: 72px;"></th>
            <th scope="col" id="img" class="manage-column column-title" style="width: 104px;"></th>
            <th scope="col" id="name" class="manage-column column-title"><?php _e('Name', 'wpinimat_languages'); ?></th>
            <th scope="col" id="author" class="manage-column column-title"><?php _e('Author', 'wpinimat_languages'); ?></th>
            <th scope="col" id="date" class="manage-column column-title"><?php _e('Date', 'wpinimat_languages'); ?></th>
            <th scope="col" id="finished" class="manage-column column-title"><?php _e('Finished', 'wpinimat_languages'); ?></th>
            <th scope="col" id="description" class="manage-column column-title" style="width: 400px"><?php _e('Description', 'wpinimat_languages'); ?></th>
        </tr>
    </thead>
    
    <tfoot>
        <tr>
            <th scope="col" id="edit" class="manage-column column-cb check-column" style="width: 72px;"></th>
            <th scope="col" id="img" class="manage-column column-title" style="width: 104px;"></th>
            <th scope="col" id="name" class="manage-column column-title"><?php _e('Name', 'wpinimat_languages'); ?></th>
            <th scope="col" id="author" class="manage-column column-title"><?php _e('Author', 'wpinimat_languages'); ?></th>
            <th scope="col" id="date" class="manage-column column-title"><?php _e('Date', 'wpinimat_languages'); ?></th>
            <th scope="col" id="finished" class="manage-column column-title"><?php _e('Finished', 'wpinimat_languages'); ?></th>
            <th scope="col" id="description" class="manage-column column-title" style="width: 400px"><?php _e('Description', 'wpinimat_languages'); ?></th>
        </tr>
    </tfoot>

<?php

foreach ($sql as $key => $value) {
		
	?>
	<tbody id="the-list">
		<tr>
			<th scope="row" class="check-column" style="text-align: center;">
			<form action="admin.php">
				<input type="hidden" name="page" value="wpinimat/classifier_creatures/view" />
				<input type="hidden" name="select_creature" value=<?php echo ($sql[$key]["id"] - 1);?> />
				<input type="submit" id="btnsubmit" value="<?php echo __('View', 'wpinimat_languages'); ?>" class="button" style="margin: 10px;" />
			</form>
            <?php
            if (current_user_can('manage_options') == TRUE) {
				?>
				<form action="admin.php">
					<input type="hidden" name="page" value="wpinimat/classifier_creatures/edit" />
					<input type="hidden" name="select_creature" value=<?php echo ($sql[$key]["id"] - 1);?> />
					<input type="submit" id="btnsubmit" value="<?php echo __('Edit', 'wpinimat_languages'); ?>" class="button" style="margin: 10px;" />
				</form>
                <?php
			}
			?>
			</th>
			<td style="vertical-align: middle;">
				<img src="<?php
					
					if ($sql[$key]["textured"] != '') {
						
						$textured = unserialize($sql[$key]["textured"]);
						echo WPINIMAT_PLUGIN_URL . 'upload/th/' . $textured["file_name"];
						
					} elseif ($sql[$key]["modeled"] != '') {
						
						$modeled = unserialize($sql[$key]["modeled"]);
						echo WPINIMAT_PLUGIN_URL . 'upload/th/' . $modeled["file_name"];
						
					} elseif ($sql[$key]["sketch"] != '') {
						
						$sketch = unserialize($sql[$key]["sketch"]);
						echo WPINIMAT_PLUGIN_URL . 'upload/th/' . $sketch["file_name"];
						
					} else {
						
						// no image
						echo WPINIMAT_PLUGIN_URL . 'img/not-img.png';
						
					}
						
				?>" width="100" height="100" />
			</td>
			<td style="vertical-align: middle;">
				<b><?php echo ucfirst(strtolower($sql[$key]["name"])); ?></b>
			</td>
			<td style="vertical-align: middle;">
				<?php
					if ($sql[$key]["id_author"] != 0) {
						
						echo get_userdata($sql[$key]["id_author"])->display_name;
						
					} else {
						
						echo $sql[$key]["name_author"];
						
					}
				?>
			</td>
			<td style="vertical-align: middle;">
				<?php echo mysql2date('d - m - Y', $sql[$key]["date_add"]); ?>
			</td>
			<td style="vertical-align: middle;">
				<?php
					if ($sql[$key]["finished"] == 0) {
						
						echo '<img src="' . WPINIMAT_PLUGIN_URL . 'img/close.x32.png" alt="no" />';
						
					} else {
						
						echo '<img src="' . WPINIMAT_PLUGIN_URL . 'img/correct.x32.png" alt="yes" />';
						
					}
				?>
			</td>
			<td style="vertical-align: middle;">
				<?php echo substr($sql[$key]["description"], 0, 250) . '...'; ?>
			</td>
		</tr>
	<?php

}

?>

	</tbody></table>
    
    <div class="tablenav bot">
		<div class="tablenav-pages"><?php echo $paginator->build(); ?></div>
    </div>
