<?php
require_once 'DigitalNZImport.php';
require_once 'DigitalNZItem.php';

class DigitalNZ_IndexController extends Omeka_Controller_Action
{
	
	const PROCESS_CLASS_IMPORT = 'DigitalNZ_ImportProcess';
	
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
	
	/**
	 *
	 */
	public function refreshAction()
	{	
		$args = array();
		
		//$process = ProcessDispatcher::startProcess(self::PROCESS_CLASS_IMPORT, null, $args);
		
		$overdueItems = get_db()->getTable('DigitalNZItem')->findOverdue();
		
		foreach($overdueItems as $overdue) {
			$item = get_item_by_id($overdue->item_id);

			// JSON Web Service Request Made to DNZ */
			$url = 'http://api.digitalnz.org/records/v2.json?search_text=id:"' . $overdue->dnz_id. '"&api_key=6y98irEtPSynyEbqTPfw';

			$dnzItem = json_decode(file_get_contents($url), true); 
			$dnzItem = $dnzItem['results'][0];

			// User Selection to Use Dublin-Core MetaData Standard */
			if ($overdue->is_dublin)
			{
				$item = update_item($this->item, array('public' => true), array('Dublin Core'=> $this->_convertDnz($dnzItem)));
			} 
			else 
			{ 
				$item = update_item($this->item, array('public' => true), array('Digital New Zealand' => $this->_formatDnz($dnzItem)));
			}
			
			// UPDATE ITEM DATE!!!!!!!!!!!
		}
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
		$import->added = date("Y-m-d");
		$import->save();	
				
		// Each Item Selected By the User is Comitted to an Omeka Item 
		foreach($results_check as $result)
		{
			$this->_createItem($result, $collection_id);
		}
		
		// Item's Successfully Added and User is Redirected with Success Message 
		$this->_helper->redirector->goto('index', 'index', null, array('message' => 'success'));
	}
	
	/**
	 *  @param $result - DNZ Item Specified by User, $collection_id - 
	 */
	public function _createItem($result, $collection_id)
	{
		// JSON Web Service Request Made to DNZ */
		$url = 'http://api.digitalnz.org/records/v2.json?search_text=id:"' . $result . '"&api_key=6y98irEtPSynyEbqTPfw';
							
		$dnzItem = json_decode(file_get_contents($url), true); 
		$dnzItem = $dnzItem['results'][0];
		
		$importItem = new DigitalNZItem();
			
		// User Selection to Use Dublin-Core MetaData Standard */
		if (get_option("use_dublin_core"))
		{
			$item = insert_item(array('public' => true, 'collection_id' => $collection_id), 
                				array('Dublin Core' => $this->_convertDnz($dnzItem))); 
			$importItem->is_dublin = 1;
		} 
		else 
		{ 
			$item = insert_item(array('public' => true, 'collection_id' => $collection_id), 
                				array('Digital New Zealand' => $this->_formatDnz($dnzItem)));
			$importItem->is_dublin = 0;
		}
		
		$importItem->item_id = $item->id;
		$importItem->collection_id = $collection_id;
		$importItem->dnz_id = $dnzItem['id'];
		$importItem->added = date("Y-m-d");
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
