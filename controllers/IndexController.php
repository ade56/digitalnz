<?php
require_once 'DigitalNZImport.php';
require_once 'DigitalNZItem.php';

class DigitalNZ_IndexController extends Omeka_Controller_Action
{
    /**
     * Front Digital New Zealand page.
     */
    public function indexAction() 
	{
		$message = $this->_getParam('message');
		
		if(!get_option('digitalnz_api_key')) $message = 'error_key';
		
		if($message) $this->view->assign('message', $message);	

		// Collection Table Retrieved and Queried to Retrieve ID & Name Fields */
		$collectionTable = get_db()->getTable('Collection'); 
       	
		$select = $collectionTable->getSelect();
		
		// Array Containing All Collection IDs and Names Populated and Assigned to Index-View */
		$collection_array = array(); 

       	foreach($collectionTable->fetchObjects($select) as $col)
		{
			$collection_array[$col->id] = $col->name;
		}		
		$this->view->assign('collection_array', $collection_array);
	}
	
	public function addAction()
    {
		$results_check = $_POST['results_check_box'];
		$collection = $_POST['digitalNZ_collection'];
	
		// Asserts Item was Checked and Redirects is In-Valid 
		if(!$results_check[0])
		{
			$this->_helper->redirector->goto('index', 'index', null, array('message' => 'error'));
		}
		
		$collection_id = '';
		
		// New Collection Created and Retrieved from DB so Items can be Added Based on ID 
		if($collection == "exist_collection" && $_POST['digitalNZ_collection_select'] != 'Please select a collection')
		{
			$collection_id = $_POST['digitalNZ_collection_select'];		
		} 
		else if($collection == "new_collection" && $_POST['new_collection']!= 'Please input a name')
		{
			$collection = insert_collection(array('name'=> $_POST['new_collection'], 'public'=>true, 'description'=>' '));				
			$collectionTable = get_db()->getTable('Collection');
	       	$select = $collectionTable->getSelect();
	       	$select->where('name = ?', $_POST['new_collection']);
			$collection_id = $collectionTable->fetchObject($select)->id;		
		}	
		
		//
		$import = new DigitalNZImport();
		$import->collection_id = $collection_id;
		$import->save();	
				
		// Each Item Selected By the User is Comitted to an Omeka Item 
		foreach($results_check as $result)
		{
			$this->_createItem($result, $collection_id, $import->id);
		}
		
		// Item's Successfully Added and User is Redirected with Success Message 
		$this->_helper->redirector->goto('index', 'index', null, array('message' => 'success'));
	}
	
	/**
	 *  @param $result - DNZ Item Specified by User, $collection_id - 
	 */
	public function _createItem($result, $collection_id, $import_id)
	{
		// JSON Web Service Request Made to DNZ */
		$url = 'http://api.digitalnz.org/records/v2.json?search_text=id:"' . $result . '"&api_key=6y98irEtPSynyEbqTPfw';
							
		$dnzItem = json_decode(file_get_contents($url), true); 
		$dnzItem = $dnzItem['results'][0];

		// User Selection to Use Dublin-Core MetaData Standard */
		if (get_option("use_dublin_core"))
		{
			$item = insert_item(array('public' => true, 'collection_id' => $collection_id), 
                				array('Dublin Core' => $this->_convertDnz($dnzItem))); 
		} 
		else 
		{ 
			$item = insert_item(array('public' => true, 'collection_id' => $collection_id), 
                				array('Digital New Zealand' => $this->_formatDnz($dnzItem)));
		}
		
		$importItem = new DigitalNZItem();
		$importItem->import_id = $import_id;
		$importItem->item_id = $item->id;
		$importItem->save();
		release_object($importItem);
			
		// File Content is Inserted *
		try {
			$url = $dnzItem['large_thumbnail_url'];	
			
			if(!$url) $url = $dnzItem['thumbnail_url'];
			
			$file = insert_files_for_item($item,  'Url', array($url));
			release_object($file);
		} catch (Exception $e) {
			
		}	
	}
	
	/**
	 *  DigitalNZ MetaData Mapping to Dublin-Core Standard -- Requires More fields..?
	 *
	 *  @param array 
	 */
	public function _convertDnz($dnzItem)
	{
		$dublinCore = array(
            'Title' => array(
                array('text' => $dnzItem['title'], 'html' => false)
            ),
			'Description' => array(
                array('text' => $dnzItem['description'], 'html' => false)
            ),
			'Creator' => array(
                array('text' => $dnzItem['author'], 'html' => false)
            ),
			'Source' => array(
                array('text' => $dnzItem['content_provider'], 'html' => false)
            ),
			'Publisher' => array(
                array('text' => $dnzItem['publisher'], 'html' => false)
            ),
			'Date' => array(
                array('text' => $dnzItem['date'], 'html' => false)
            ),
			'Rights' => array(
                array('text' => $dnzItem['object_copyright'], 'html' => false)
            ),
			'Format' => array(
                array('text' => $dnzItem['dctype'], 'html' => false)
            )
        );
		
		return $dublinCore;
	}
	
	/**
	 * DigitalNZ MetaData Formatted as Omeka Element Array Before Insertion
	 *
	 * @param array
	 */
	public function _formatDnz($dnzItem)
	{
		$digitalNZ = array();
		
		foreach($dnzItem as $key => $value) {
			$digitalNZ[$key] = array(
				array('text' => $value, 'html' => false)
			);
		}
		
		return $digitalNZ;
	}
}
