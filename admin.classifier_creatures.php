<?php
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

require( WPINIMAT_PLUGIN_PATH . '/class/func.php' );

require( WPINIMAT_PLUGIN_PATH . '/class/ajax_paginator.php' );

global $wpdb;

$functions = new functions();

// instantiate mysqli connection
// CHANGE THESE SETTINGS
//$conn = new mysqli('localhost', 'root', '','test') ;
$nRecordPage = 5; // Number of records by page

$query = "SELECT id, name FROM ".$wpdb->prefix."inimat_creatures ";

//$query = "SELECT * FROM customers ";

// if there is a a search query
$searchQuery = !empty($_GET['search']) ? $searchQuery = $_GET['search'] : ''; 
$nPage = empty($_GET['page']) ? 1 : mysql_real_escape_string($_GET['page']); 

$con = $wpdb -> db_connect();

$paginator = new AjaxPaginator($nPage, $nRecordPage, $query, $con);

$paginator -> searchQuery = $searchQuery;

// database field to search in
//$pagination->fields = 'name';
// or try array
// passing an array makes the search text to search in the name or the id
$paginator -> fields = array('name','id');

// Get the paginated rows
try{
	$rows = $paginator->paginate();
}catch (Exception $e){
	echo $e->getMessage();
}

?>
<script type="text/javascript">
	$('.paginator a').click(function () {
		$('#listing_container').Paginate(this.id);
		return false;	
	});
</script>

    <table border="0" cellpadding="2" cellspacing="0" class="listing">
	<tr>
		<th nowrap="nowrap" width="40"> ID</th>
		<th nowrap="nowrap" width="450" align='left'>Name</th>
	</tr>
<?php

foreach($rows as $row){
	echo "<tr>";
	echo "<td nowrap='nowrap' align='center'>{$row['id']}</td>";
	echo "<td nowrap='nowrap' align='left'>{$row['name']}</td>";
	echo "</tr>";
}

echo "</table><br />";

echo "<div class='paginator'> " . $paginator->getLinks () ;

echo "<br /><p>Page " . $paginator->pageId . "  of " . $paginator->totalPages."</p>". "</div>";

/**
 *-------------------PROCECO de HAVER Funcionar!s
if ( isset($_GET['type']) ) { $getType = $_GET['type']; } else { $getType = false; }

?>

<ul class="subsubsub">
	<li>
		<a href="<?php echo WPINIMAT_CC_URL;?>" <?php if($functions -> is_page_current($getType)) { echo 'class="current"'; } ?> ><?php _e('All', 'wpinimat_languages'); ?>&nbsp;<span class="count">(<?php echo $functions -> count_creatures(); ?>)</span></a>&nbsp;|
	</li>
	<li>
    	<a href="<?php echo WPINIMAT_CC_URL.'&type=material'; ?>" <?php if($functions -> is_page_current($getType, 'material')) { echo 'class="current"'; } ?> ><?php _e('Material', 'wpinimat_languages'); ?>&nbsp;<span class="count">(<?php echo $functions -> count_creatures('material'); ?>)</span></a>&nbsp;|
	</li>
    <li>
    	<a href="<?php echo WPINIMAT_CC_URL.'&type=astral'; ?>" <?php if($functions -> is_page_current($getType, 'astral')) { echo 'class="current"'; } ?> ><?php _e('Astral', 'wpinimat_languages'); ?>&nbsp;<span class="count">(<?php echo $functions -> count_creatures('astral'); ?>)</span></a>&nbsp;|
	</li>
    <li>
    	<a href="<?php echo WPINIMAT_CC_URL.'&type=guardian'; ?>" <?php if($functions -> is_page_current($getType, 'guardian')) { echo 'class="current"'; } ?> ><?php _e('Guardian', 'wpinimat_languages'); ?>&nbsp;<span class="count">(<?php echo $functions -> count_creatures('guardian'); ?>)</span></a>&nbsp;|
	</li>
    <li>
    	<a href="<?php echo WPINIMAT_CC_URL.'&type=samus'; ?>" <?php if($functions -> is_page_current($getType, 'samus')) { echo 'class="current"'; } ?> ><?php _e('Samus', 'wpinimat_languages'); ?>&nbsp;<span class="count">(<?php echo $functions -> count_creatures('samus'); ?>)</span></a>
	</li>
</ul>
<?php
-------------------*/
?>

<form id="posts-filter" action="" method="get">
<p class="search-box">
	<label class="screen-reader-text" for="media-search-input"><?php _e('Search creatures', 'wpinimat_languages'); ?>:</label>
    <input type="search" id="media-search-input" name="s" value="">
    <input type="submit" name="" id="search-submit" class="button" value="<?php _e('Search creatures', 'wpinimat_languages'); ?>">
</p>

<div class="tablenav top">
<?php if (current_user_can('manage_options')) { ?>
    <div class="alignleft actions">
    	<select name="action">
        	<option value="0" selected="selected"><?php _e('Bulk Actions', 'wpinimat_languages'); ?></option>
            <option value="delete"><?php _e('Delete permanently', 'wpinimat_languages'); ?></option>
        </select>
        <input type="submit" name="" id="doaction" class="button action" value="<?php _e('Apply', 'wpinimat_languages'); ?>">
	</div>
<?php } ?>

	<div class="alignleft actions">
		<select name="m">
        	<option selected="selected" value="0"><?php _e('Show all states', 'wpinimat_languages'); ?></option>
            <option value="finished"><?php _e('Finished', 'wpinimat_languages'); ?></option>
            <option value="not_finished"><?php _e('Not finished', 'wpinimat_languages'); ?></option>
		</select>
        <input type="submit" name="" id="post-query-submit" class="button" value="<?php _e('Filter', 'wpinimat_languages'); ?>">
	</div>
</div>

<table class="wp-list-table widefat fixed media" cellspacing="0">
<thead>
	<tr>
    	<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
        	<label class="screen-reader-text" for="cb-select-all-1"><?php _e('Select all', 'wpinimat_languages'); ?></label>
            <input id="cb-select-all-1" type="checkbox">
        </th>
        <th scope="col" id="thumbnail" class="manage-column column-thumbnail">
        </th>
        <th scope="col" id="title" class="manage-column column-name">
        	<span><?php _e('Name', 'wpinimat_languages'); ?></span><span class="sorting-indicator"></span>
        </th>
        <th scope="col" id="author" class="manage-column column-author">
        	<span><?php _e('Author', 'wpinimat_languages'); ?></span><span class="sorting-indicator"></span>
      	</th>
	</tr>
</thead>

<tfoot>
	<tr>
    	<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
        	<label class="screen-reader-text" for="cb-select-all-1"><?php _e('Select all', 'wpinimat_languages'); ?></label>
            <input id="cb-select-all-1" type="checkbox">
        </th>
        <th scope="col" id="thumbnail" class="manage-column column-thumbnail">
        </th>
        <th scope="col" id="title" class="manage-column column-name">
        	<span><?php _e('Name', 'wpinimat_languages'); ?></span><span class="sorting-indicator"></span>
        </th>
        <th scope="col" id="author" class="manage-column column-author">
        	<span><?php _e('Author', 'wpinimat_languages'); ?></span><span class="sorting-indicator"></span>
      	</th>
	</tr>
</tfoot>

<tbody id="the-list">
	<tr>
    	<th scope="row" class="check-column">
        	<label class="screen-reader-text" for="cb-select-694">Elige Idea para hacer la historia inimat</label>
				<input type="checkbox" name="media[]" id="cb-select-694" value="694">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=694&amp;action=edit" title="Editar “Idea para hacer la historia inimat”">
					<img width="60" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-content/uploads/2013/02/Idea-para-hacer-la-historia-inimat1-150x150.png" class="attachment-80x60" alt="Idea para hacer la historia inimat">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=694&amp;action=edit" title="Editar “Idea para hacer la historia inimat”">
				Idea para hacer la historia inimat</a>
			 - <span class="post-state">Imagen de fondo</span></strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=694&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=694&amp;_wpnonce=7ed956456d">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=694" title="Ver “Idea para hacer la historia inimat”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">admin</td>
			<td class="parent column-parent">(Sin adjuntar)<br>
							<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','694' ); return false;" href="#the-list">
					Adjuntar</a>
			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=694" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">19/02/2013</td>
	</tr>
	<tr id="post-691" class="author-self status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-691">Elige Idea para hacer la historia inimat</label>
				<input type="checkbox" name="media[]" id="cb-select-691" value="691">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=691&amp;action=edit" title="Editar “Idea para hacer la historia inimat”">
					<img width="60" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-content/uploads/2013/02/Idea-para-hacer-la-historia-inimat-150x150.png" class="attachment-80x60" alt="Idea para hacer la historia inimat">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=691&amp;action=edit" title="Editar “Idea para hacer la historia inimat”">
				Idea para hacer la historia inimat</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=691&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=691&amp;_wpnonce=5058772f6d">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=691" title="Ver “Repositorio del plugin del proyecto (wp-inimat)”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">admin</td>
			<td class="parent column-parent"><strong>
									<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=687&amp;action=edit">
						Repositorio del plugin del proyecto (wp-inimat)</a></strong>,
				19/02/2013			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=691" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">19/02/2013</td>
	</tr>
	<tr id="post-688" class="alternate author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-688">Elige wordpress plugin</label>
				<input type="checkbox" name="media[]" id="cb-select-688" value="688">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=688&amp;action=edit" title="Editar “wordpress plugin”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="wordpress plugin">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=688&amp;action=edit" title="Editar “wordpress plugin”">
				wordpress plugin</a>
			</strong>
			<p>
JPEG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=688&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=688&amp;_wpnonce=aa00ed7270">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=688" title="Ver “Repositorio del plugin del proyecto (wp-inimat)”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Suscriptor [demo]</td>
			<td class="parent column-parent"><strong>
									<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=687&amp;action=edit">
						Repositorio del plugin del proyecto (wp-inimat)</a></strong>,
				01/02/2013			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=688" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">01/02/2013</td>
	</tr>
	<tr id="post-679" class="author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-679">Elige plugin_inimat</label>
				<input type="checkbox" name="media[]" id="cb-select-679" value="679">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=679&amp;action=edit" title="Editar “plugin_inimat”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="plugin_inimat">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=679&amp;action=edit" title="Editar “plugin_inimat”">
				plugin_inimat</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=679&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=679&amp;_wpnonce=a7a9a36c06">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=679" title="Ver “Heremienta para compartir y editar el gu”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Suscriptor [demo]</td>
			<td class="parent column-parent"><strong>
				Heremienta para compartir y editar el gu</strong>,
				18/01/2013			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=679" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">18/01/2013</td>
	</tr>
	<tr id="post-673" class="alternate author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-673">Elige Historia Inimat [16-01-2013]</label>
				<input type="checkbox" name="media[]" id="cb-select-673" value="673">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=673&amp;action=edit" title="Editar “Historia Inimat [16-01-2013]”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="Historia Inimat [16-01-2013]">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=673&amp;action=edit" title="Editar “Historia Inimat [16-01-2013]”">
				Historia Inimat [16-01-2013]</a>
			</strong>
			<p>
APPLICATION/ZIP			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=673&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=673&amp;_wpnonce=944daec984">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=673" title="Ver “Historia”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Suscriptor [demo]</td>
			<td class="parent column-parent"><strong>
				Historia</strong>,
				16/01/2013			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=673" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">16/01/2013</td>
	</tr>
	<tr id="post-662" class="author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-662">Elige 765-default-avatar</label>
				<input type="checkbox" name="media[]" id="cb-select-662" value="662">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=662&amp;action=edit" title="Editar “765-default-avatar”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="765-default-avatar">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=662&amp;action=edit" title="Editar “765-default-avatar”">
				765-default-avatar</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=662&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=662&amp;_wpnonce=f65ac16086">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=662" title="Ver “¿Quienes somos nosotros?”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Editor [demo]</td>
			<td class="parent column-parent"><strong>
									<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=53&amp;action=edit">
						¿Quienes somos nosotros?</a></strong>,
				12/01/2013			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=662" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">12/01/2013</td>
	</tr>
	<tr id="post-608" class="alternate author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-608">Elige logo3</label>
				<input type="checkbox" name="media[]" id="cb-select-608" value="608">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=608&amp;action=edit" title="Editar “logo3”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="logo3">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=608&amp;action=edit" title="Editar “logo3”">
				logo3</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=608&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=608&amp;_wpnonce=ac02f8d37b">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=608" title="Ver “logo3”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Editor [demo]</td>
			<td class="parent column-parent">(Sin adjuntar)<br>
							<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','608' ); return false;" href="#the-list">
					Adjuntar</a>
			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=608" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">10/01/2013</td>
	</tr>
	<tr id="post-607" class="author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-607">Elige logo3</label>
				<input type="checkbox" name="media[]" id="cb-select-607" value="607">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=607&amp;action=edit" title="Editar “logo3”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="logo3">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=607&amp;action=edit" title="Editar “logo3”">
				logo3</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=607&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=607&amp;_wpnonce=d6277059f0">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=607" title="Ver “logo3”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Editor [demo]</td>
			<td class="parent column-parent">(Sin adjuntar)<br>
							<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','607' ); return false;" href="#the-list">
					Adjuntar</a>
			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=607" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">10/01/2013</td>
	</tr>
	<tr id="post-605" class="alternate author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-605">Elige logo2</label>
				<input type="checkbox" name="media[]" id="cb-select-605" value="605">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=605&amp;action=edit" title="Editar “logo2”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="logo2">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=605&amp;action=edit" title="Editar “logo2”">
				logo2</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=605&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=605&amp;_wpnonce=f405e311ae">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=605" title="Ver “logo2”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Editor [demo]</td>
			<td class="parent column-parent">(Sin adjuntar)<br>
							<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','605' ); return false;" href="#the-list">
					Adjuntar</a>
			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=605" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">10/01/2013</td>
	</tr>
	<tr id="post-598" class="author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-598">Elige Pantallazo+del+2012-07-26+15_23_36[1]</label>
				<input type="checkbox" name="media[]" id="cb-select-598" value="598">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=598&amp;action=edit" title="Editar “Pantallazo+del+2012-07-26+15_23_36[1]”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="Pantallazo+del+2012-07-26+15_23_36[1]">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=598&amp;action=edit" title="Editar “Pantallazo+del+2012-07-26+15_23_36[1]”">
				Pantallazo+del+2012-07-26+15_23_36[1]</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=598&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=598&amp;_wpnonce=8b9901bee6">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=598" title="Ver “Sistema de dialogos animados en Blender Game Engine”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Suscriptor [demo]</td>
			<td class="parent column-parent"><strong>
									<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=301&amp;action=edit">
						Sistema de dialogos animados en Blender Game Engine</a></strong>,
				07/01/2013			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=598" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">07/01/2013</td>
	</tr>
	<tr id="post-568" class="alternate author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-568">Elige bannerya3</label>
				<input type="checkbox" name="media[]" id="cb-select-568" value="568">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=568&amp;action=edit" title="Editar “bannerya3”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="bannerya3">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=568&amp;action=edit" title="Editar “bannerya3”">
				bannerya3</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=568&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=568&amp;_wpnonce=187c5709f1">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=568" title="Ver “¡Ya tenemos 50 Inimats!”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Editor [demo]</td>
			<td class="parent column-parent"><strong>
									<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=316&amp;action=edit">
						¡Ya tenemos 50 Inimats!</a></strong>,
				05/01/2013			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=568" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">05/01/2013</td>
	</tr>
	<tr id="post-562" class="author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-562">Elige check-list</label>
				<input type="checkbox" name="media[]" id="cb-select-562" value="562">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=562&amp;action=edit" title="Editar “check-list”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="check-list">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=562&amp;action=edit" title="Editar “check-list”">
				check-list</a>
			</strong>
			<p>
JPEG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=562&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=562&amp;_wpnonce=3665c21ef0">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=562" title="Ver “check-list”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Suscriptor [demo]</td>
			<td class="parent column-parent">(Sin adjuntar)<br>
							<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','562' ); return false;" href="#the-list">
					Adjuntar</a>
			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=562" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">04/01/2013</td>
	</tr>
	<tr id="post-513" class="alternate author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-513">Elige Inicio2[1]</label>
				<input type="checkbox" name="media[]" id="cb-select-513" value="513">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=513&amp;action=edit" title="Editar “Inicio2[1]”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="Inicio2[1]">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=513&amp;action=edit" title="Editar “Inicio2[1]”">
				Inicio2[1]</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=513&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=513&amp;_wpnonce=4604668e5a">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=513" title="Ver “Inicio2[1]”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Suscriptor [demo]</td>
			<td class="parent column-parent">(Sin adjuntar)<br>
							<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','513' ); return false;" href="#the-list">
					Adjuntar</a>
			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=513" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">04/01/2013</td>
	</tr>
	<tr id="post-512" class="author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-512">Elige Biso[1]</label>
				<input type="checkbox" name="media[]" id="cb-select-512" value="512">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=512&amp;action=edit" title="Editar “Biso[1]”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="Biso[1]">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=512&amp;action=edit" title="Editar “Biso[1]”">
				Biso[1]</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=512&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=512&amp;_wpnonce=67bfc0af91">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=512" title="Ver “Biso[1]”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Suscriptor [demo]</td>
			<td class="parent column-parent">(Sin adjuntar)<br>
							<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','512' ); return false;" href="#the-list">
					Adjuntar</a>
			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=512" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">04/01/2013</td>
	</tr>
	<tr id="post-507" class="alternate author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-507">Elige El curso de iniciación</label>
				<input type="checkbox" name="media[]" id="cb-select-507" value="507">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=507&amp;action=edit" title="Editar “El curso de iniciación”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="El curso de iniciación">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=507&amp;action=edit" title="Editar “El curso de iniciación”">
				El curso de iniciación</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=507&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=507&amp;_wpnonce=873b8fe378">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=507" title="Ver “El curso de iniciación”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Suscriptor [demo]</td>
			<td class="parent column-parent">(Sin adjuntar)<br>
							<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','507' ); return false;" href="#the-list">
					Adjuntar</a>
			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=507" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">04/01/2013</td>
	</tr>
	<tr id="post-497" class="author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-497">Elige Medidor</label>
				<input type="checkbox" name="media[]" id="cb-select-497" value="497">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=497&amp;action=edit" title="Editar “Medidor”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="Medidor">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=497&amp;action=edit" title="Editar “Medidor”">
				Medidor</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=497&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=497&amp;_wpnonce=18bf35a36f">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=497" title="Ver “Medidor”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Suscriptor [demo]</td>
			<td class="parent column-parent">(Sin adjuntar)<br>
							<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','497' ); return false;" href="#the-list">
					Adjuntar</a>
			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=497" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">04/01/2013</td>
	</tr>
	<tr id="post-462" class="alternate author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-462">Elige captura</label>
				<input type="checkbox" name="media[]" id="cb-select-462" value="462">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=462&amp;action=edit" title="Editar “captura”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="captura">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=462&amp;action=edit" title="Editar “captura”">
				captura</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=462&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=462&amp;_wpnonce=79f6e96410">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=462" title="Ver “captura”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Suscriptor [demo]</td>
			<td class="parent column-parent">(Sin adjuntar)<br>
							<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','462' ); return false;" href="#the-list">
					Adjuntar</a>
			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=462" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">04/01/2013</td>
	</tr>
	<tr id="post-459" class="author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-459">Elige editor</label>
				<input type="checkbox" name="media[]" id="cb-select-459" value="459">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=459&amp;action=edit" title="Editar “editor”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="editor">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=459&amp;action=edit" title="Editar “editor”">
				editor</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=459&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=459&amp;_wpnonce=5753d175b5">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=459" title="Ver “editor”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Suscriptor [demo]</td>
			<td class="parent column-parent">(Sin adjuntar)<br>
							<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','459' ); return false;" href="#the-list">
					Adjuntar</a>
			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=459" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">04/01/2013</td>
	</tr>
	<tr id="post-448" class="alternate author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-448">Elige Pantallazo[1]</label>
				<input type="checkbox" name="media[]" id="cb-select-448" value="448">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=448&amp;action=edit" title="Editar “Pantallazo[1]”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="Pantallazo[1]">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=448&amp;action=edit" title="Editar “Pantallazo[1]”">
				Pantallazo[1]</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=448&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=448&amp;_wpnonce=e8942052a1">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=448" title="Ver “Pantallazo[1]”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Suscriptor [demo]</td>
			<td class="parent column-parent">(Sin adjuntar)<br>
							<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','448' ); return false;" href="#the-list">
					Adjuntar</a>
			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=448" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">04/01/2013</td>
	</tr>
	<tr id="post-439" class="author-other status-inherit" valign="top">
		<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-439">Elige render vexor</label>
				<input type="checkbox" name="media[]" id="cb-select-439" value="439">
					</th>
		<td class="column-icon media-icon">				<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=439&amp;action=edit" title="Editar “render vexor”">
					<img width="46" height="60" src="http://localhost/CMS-Pruebas/wordpress/wp-includes/images/crystal/default.png" class="attachment-80x60" alt="render vexor">				</a>

		</td>
		<td class="title column-title"><strong>
						<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=439&amp;action=edit" title="Editar “render vexor”">
				render vexor</a>
			</strong>
			<p>
PNG			</p>
<div class="row-actions"><span class="edit"><a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/post.php?post=439&amp;action=edit">Editar</a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="post.php?action=delete&amp;post=439&amp;_wpnonce=74f7d4a978">Borrar permanentemente</a> | </span><span class="view"><a href="http://localhost/CMS-Pruebas/wordpress/?attachment_id=439" title="Ver “render vexor”" rel="permalink">Ver</a></span></div>		</td>
		<td class="author column-author">Suscriptor [demo]</td>
			<td class="parent column-parent">(Sin adjuntar)<br>
							<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','439' ); return false;" href="#the-list">
					Adjuntar</a>
			</td>
		<td class="comments column-comments num">
			<div class="post-com-count-wrapper">
<a href="http://localhost/CMS-Pruebas/wordpress/wp-admin/edit-comments.php?p=439" title="0 pendientes" class="post-com-count"><span class="comment-count">0</span></a>			</div>
		</td>
		<td class="date column-date">04/01/2013</td>
	</tr>
	</tbody>
</table>
	<div class="tablenav bottom">

		<div class="alignleft actions">
			<select name="action2">
<option value="-1" selected="selected">Acciones en lote</option>
	<option value="delete">Borrar permanentemente</option>
</select>
<input type="submit" name="" id="doaction2" class="button action" value="Aplicar">
		</div>
		<div class="alignleft actions">
		</div>
<div class="tablenav-pages"><span class="displaying-num">40 elementos</span>
<span class="pagination-links"><a class="first-page disabled" title="Ir a la primera página" href="http://localhost/CMS-Pruebas/wordpress/wp-admin/upload.php">«</a>
<a class="prev-page disabled" title="Ir a la página anterior" href="http://localhost/CMS-Pruebas/wordpress/wp-admin/upload.php?paged=1">‹</a>
<span class="paging-input">1 de <span class="total-pages">2</span></span>
<a class="next-page" title="Ir a la página siguiente" href="http://localhost/CMS-Pruebas/wordpress/wp-admin/upload.php?paged=2">›</a>
<a class="last-page" title="Ir a la última página" href="http://localhost/CMS-Pruebas/wordpress/wp-admin/upload.php?paged=2">»</a></span></div>
		<br class="clear">
	</div>

<div id="ajax-response"></div>
	<div id="find-posts" class="find-box" style="display:none;">
		<div id="find-posts-head" class="find-box-head">Buscar entradas o páginas</div>
		<div class="find-box-inside">
			<div class="find-box-search">
				
				<input type="hidden" name="affected" id="affected" value="">
				<input type="hidden" id="_ajax_nonce" name="_ajax_nonce" value="a3e78052de">				<label class="screen-reader-text" for="find-posts-input">Buscar</label>
				<input type="text" id="find-posts-input" name="ps" value="">
				<span class="spinner"></span>
				<input type="button" id="find-posts-search" value="Buscar" class="button">
			</div>
			<div id="find-posts-response"></div>
		</div>
		<div class="find-box-buttons">
			<input id="find-posts-close" type="button" class="button alignleft" value="Cerrar">
			<input type="submit" name="find-posts-submit" id="find-posts-submit" class="button button-primary alignright" value="Elegir">		</div>
	</div>
<br class="clear">

</form>