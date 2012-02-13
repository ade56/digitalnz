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
    
    //Elements Set to Match those from DNZ Web Service
    $elements = array(array('name' => 'iwihapu_authority', 'data_type' => 'Tiny Text'),
                    array('name' => 'display_date', 'data_type' => 'Tiny Text'),
                    array('name' => 'object_copyright', 'data_type' => 'Tiny Text'),
                    array('name' => 'marsden_code', 'data_type' => 'Tiny Text'),
                    array('name' => 'metadata_url', 'data_type' => 'Tiny Text'),
                    array('name' => 'is_catalog_record', 'data_type' => 'Tiny Text'),
                    array('name' => 'preferred_term', 'data_type' => 'Tiny Text'),
                    array('name' => 'category', 'data_type' => 'Tiny Text'),
                    array('name' => 'related_term', 'data_type' => 'Tiny Text'),
                    array('name' => 'place_authority', 'data_type' => 'Tiny Text'),
                    array('name' => 'large_thumbnail_url', 'data_type' => 'Tiny Text'),
                    array('name' => 'author', 'data_type' => 'Tiny Text'),
                    array('name' => 'title', 'data_type' => 'Tiny Text'),
                    array('name' => 'dnztype', 'data_type' => 'Tiny Text'),
                    array('name' => 'no_landing_page', 'data_type' => 'Tiny Text'),
                    array('name' => 'atl_free_download', 'data_type' => 'Tiny Text'),
                    array('name' => 'geo_co_ords', 'data_type' => 'Tiny Text'),
                    array('name' => 'published', 'data_type' => 'Tiny Text'),
                    array('name' => 'content_provider', 'data_type' => 'Tiny Text'),
                    array('name' => 'library_collection', 'data_type' => 'Tiny Text'),
                    array('name' => 'atl_purchasable_download', 'data_type' => 'Tiny Text'),
                    array('name' => 'atl_purchasable', 'data_type' => 'Tiny Text'),
                    array('name' => 'collection_parent', 'data_type' => 'Tiny Text'),
                    array('name' => 'source_url', 'data_type' => 'Tiny Text'),
                    array('name' => 'dctype', 'data_type' => 'Tiny Text'),
                    array('name' => 'narrower_term', 'data_type' => 'Tiny Text'),
                    array('name' => 'imagetype_authority', 'data_type' => 'Tiny Text'),
                    array('name' => 'heading_type', 'data_type' => 'Tiny Text'),
                    array('name' => 'object_license', 'data_type' => 'Tiny Text'),
                    array('name' => 'display_collection', 'data_type' => 'Tiny Text'),
                    array('name' => 'syndication_date', 'data_type' => 'Tiny Text'),
                    array('name' => 'date', 'data_type' => 'Tiny Text'),
                    array('name' => 'id', 'data_type' => 'Tiny Text'),
                    array('name' => 'additional_description', 'data_type' => 'Tiny Text'),
                    array('name' => 'child_series', 'data_type' => 'Tiny Text'),
                    array('name' => 'broader_term', 'data_type' => 'Tiny Text'),
                    array('name' => 'publisher', 'data_type' => 'Tiny Text'),
                    array('name' => 'object_url', 'data_type' => 'Tiny Text'),
                    array('name' => 'atl_physical_viewability', 'data_type' => 'Tiny Text'),
                    array('name' => 'collection_root', 'data_type' => 'Tiny Text'),
                    array('name' => 'unpreferred_term', 'data_type' => 'Tiny Text'),
                    array('name' => 'subject_authority', 'data_type' => 'Tiny Text'),
                    array('name' => 'atl_usage_code', 'data_type' => 'Tiny Text'),
                    array('name' => 'recordtype_authority', 'data_type' => 'Tiny Text'),
                    array('name' => 'thumbnail_url', 'data_type' => 'Tiny Text'),
                    array('name' => 'description', 'data_type' => 'Tiny Text'),
                    array('name' => 'identifier', 'data_type' => 'Tiny Text'),
                    array('name' => 'object_rights_url', 'data_type' => 'Tiny Text'),
                    array('name' => 'peer_reviewed', 'data_type' => 'Tiny Text'),
                    array('name' => 'display_url', 'data_type' => 'Tiny Text'),
                    array('name' => 'thesis_level', 'data_type' => 'Tiny Text'),
                    array('name' => 'atl_location_code', 'data_type' => 'Tiny Text'),
                    array('name' => 'shelf_location', 'data_type' => 'Tiny Text'),
                    array('name' => 'name_authority', 'data_type' => 'Tiny Text'),
                    array('name' => 'record_type', 'data_type' => 'Tiny Text'));	
	
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
        queue_js('digitalNZ-callBack');
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



