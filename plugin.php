<?php 
/**
 * @copyright -- ..
 * @license -- ..
 * @package -- ..
 */

// add plugin hooks
add_plugin_hook('install', 'digitalNZ_install');
add_plugin_hook('uninstall', 'digitalNZ_uninstall');
add_plugin_hook('config', 'digitalNZ_config');
add_plugin_hook('config_form', 'digitalNZ_config_form');
add_plugin_hook('admin_theme_header', 'digitalNZ_theme_header');
add_plugin_hook('public_append_to_items_show', 'digitalNZ_public_item');

// add plugin filters
add_filter('admin_navigation_main', 'digitalNZ_admin_nav');

/**
 * Install the plugin.
 * 
 * @return void
 */
function digitalNZ_install() 
{
	$element_name = 'Digital New Zealand';
	
	$db = get_db();
	
	// Don't install if an element set by the name 'Digital New Zealand' already exists.
	if ($db->getTable('ElementSet')->findByName($element_name)) {
	     throw new Exception('An element set by the name "' . $element_name . '" already exists. You must delete that element set to install this plugin.');
	}
	
	//Fields Retrieved from Digital New Zealand 					
	$dnzFields = json_decode(file_get_contents('http://api.digitalnz.org/records/v2.json?api_key=6y98irEtPSynyEbqTPfw&search_text=&num_results=1'), true);
	
	//Elements Set to Match those from DNZ Web Service
	$elements = array();	
	foreach($dnzFields['results'][0] as $field_name => $field_value)
	{
		$elements[] = array('name' => $field_name, 'data_type' => 'Tiny Text');
	}	
	
	insert_element_set($element_name, $elements);
	
	// create dnz imports table
    $sql = "CREATE TABLE IF NOT EXISTS `{$db->prefix}digital_nz_imports` (
       `id` int(10) unsigned NOT NULL auto_increment,
       `collection_id` int(10) unsigned NOT NULL,
       PRIMARY KEY  (`id`)
       ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

	$db->query($sql);
	
	 $sql = "CREATE TABLE IF NOT EXISTS `{$db->prefix}digital_nz_items` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `import_id` int(10) unsigned NOT NULL,
	  `item_id` int(10) unsigned DEFAULT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
	  
	$db->query($sql);	
}

/**
 * Uninstall the plugin.
 * 
 * @return void
 */
function digitalNZ_uninstall()
{
	$db = get_db();

	// Delete the "digitalnz" element set if it exists.
 	$elementSet = $db->getTable('ElementSet')->findByName('Digital New Zealand');
	if ($elementSet) {
	     $elementSet->delete();
	}
	
	// DROP all tables created during installation.
	$sql = "DROP TABLE IF EXISTS `{$db->prefix}digital_nz_imports`";
	$db->query($sql);
	$sql = "DROP TABLE IF EXISTS `{$db->prefix}digital_nz_items`";
	$db->query($sql);
}

/**
 *  Handles configuration options specified by the user 
 *
 *  @param Submitted config form data 
 */
function digitalNZ_config($post)
{
	//Dnz Results per page option
	set_option('digitalnz_per_page', $_POST['per_page']); 
	
	//User API Key for Digital NZ
	set_option('digitalnz_api_key', $_POST['api_key']);
	
	// Dublin Core or Digital New Zealand Metadata Selection 
	if($post['dublin_core']){
		set_option('use_dublin_core', 1);
	} else {
		set_option('use_dublin_core', 0);
	}
}
 
/**
 *  Display for Plugin Configuration Page
 */
function digitalNZ_config_form()
{
    $html = include 'config_form.php';
	echo $html;	
}

/** 
 *  Populates 'head' Tag for Admin Theme When Index.php View is Loaded
 *
 *  @param Zend_Controller_Request_Http 
 */
function digitalNZ_theme_header($request)
{
	if ($request->getModuleName() == 'digital-nz') { 		
        echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
		      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>';
        queue_js('digitalNZ-search');
		queue_css('digitalNZ-style');
    }
}
/**
 *  Add the admin navigation for the plugin.
 * 
 *  @return array
 */
function digitalNZ_admin_nav($navArray)
{
	//ACL list needed to limit permissions?? -- To-Do..
     $navArray['Digital NZ Import'] = uri(array('module'=>'digital-nz', 'controller'=>'index', 'action'=>'index'), 'default');

    return $navArray;
}

/** 
 * Digital New Zealand Metadata HTML form for add/edit 
 *
 * @return object
 */
function digitalNZ_metadata_form()
{
	ob_start();

	$ht .= ob_get_contents();
    ob_end_clean();

	return $ht;
}

function digitalNZ_public_item()
{
	echo '<h1> TESTING FOR SHOWING </h1>';
}


