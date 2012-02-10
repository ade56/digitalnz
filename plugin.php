<?php 
/**
 * @copyright -- ..
 * @license -- ..
 * @package -- ..
 */

define('DIGITALNZ_MAX_RESULTS_PER_PAGE', 10);
define('DIGITALNZ_DEFAULT_RESULTS_PER_PAGE', 5);

// add plugin hooks
add_plugin_hook('install', 'digitalNZ_install');
add_plugin_hook('uninstall', 'digitalNZ_uninstall');
add_plugin_hook('config', 'digitalNZ_config');
add_plugin_hook('config_form', 'digitalNZ_config_form');
add_plugin_hook('admin_theme_header', 'digitalNZ_theme_header');

// add plugin filters
add_filter('admin_navigation_main', 'digitalNZ_admin_nav');

/**
 * Install the plugin.
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
    foreach ($dnzFields['results'][0] as $field_name => $field_value) {
	$elements[] = array('name' => $field_name, 'data_type' => 'Tiny Text');
    }	
	
    insert_element_set($element_name, $elements);
	
    $sql = "CREATE TABLE IF NOT EXISTS `{$db->prefix}digital_nz_items` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `item_id` int(10) unsigned DEFAULT NULL,
	  `collection_id` int(10) unsigned NOT NULL,
	  `dnz_id` int(10) unsigned NOT NULL,
	  `is_dublin` TINYINT(1) DEFAULT 0,
	  `added` DATE NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
	  
    $db->query($sql);
        
    set_option('digitalnz_per_page', DIGITAL_DEFAULT_RESULTS_PER_PAGE);
	
    set_option('digitalnz_use_dublin_core', '1');
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
    $sql = "DROP TABLE IF EXISTS `{$db->prefix}digital_nz_items`";
    $db->query($sql);
    
    // Delete all options created
    delete_option('digitalnz_per_page');
    delete_option('digitalnz_api_key');
    delete_option('digitalnz_use_dublin_core');
    delete_option('digitalnz_terms_of_use');
}

/**
 *  Handles configuration options specified by the user 
 *
 *  @param Submitted config form data 
 */
function digitalNZ_config($post)
{
    $perPage = (int)$_POST['per_page'];
    if ($perPage <= 0) {
        $perPage = DIGITALNZ_DEFAULT_RESULTS_PER_PAGE;
    } else if ($perPage > DIGITALNZ_MAX_RESULTS_PER_PAGE) {
        $perPage = DIGITALNZ_MAX_RESULTS_PER_PAGE;
    }

    //Dnz Results per page option
    set_option('digitalnz_per_page', $perPage); 
	
    //User API Key for Digital NZ
    set_option('digitalnz_api_key', $_POST['api_key']);
	
    // Dublin Core or Digital New Zealand Metadata Selection 
    set_option('digitalnz_use_dublin_core', $_POST['digitalnz_use_dublin_core']);
	
    // Compliance with DigitalNew Zealand Terms of Use Agreement
    set_option('digitalnz_terms_of_use', $_POST['digitalnz_terms_of_use']);
}
 
/**
 *  Display for Plugin Configuration Page
 */
function digitalNZ_config_form()
{
    include 'config_form.php';
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
        queue_js('JsonCallBack');
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
 * Defines the ACL for the reports controllers.
 *
 * @param Omeka_Acl $acl Access control list
 */
function csv_import_define_acl($acl)
{
    // only allow super users and admins to import csv files
    $acl->loadResourceList(array('CsvImport_Index' => array('index')));
}


