<?php
require_once 'DigitalNZItem.php';

class DigitalNZ_IndexController extends Omeka_Controller_Action
{	
    public function indexAction() 
    {
        $message = $this->_getParam('message');
		
	if (!get_option('digitalnz_api_key') || !get_option('terms_of_use')) {
            $message = 'error_key';
        } 
		
	if ($message) {
            $this->view->assign('message', $message);
        }	

	// Collection Table Retrieved and Queried to Retrieve ID & Name Fields */
	$collectionTable = get_db()->getTable('Collection'); 
       	
	$select = $collectionTable->getSelect();
		
	// Array Containing All Collection IDs and Names Populated and Assigned to Index-View */
	$collection_array = array(); 

       	foreach ($collectionTable->fetchObjects($select) as $col) {
            $collection_array[$col->id] = $col->name;
        }		
        $this->view->assign('collection_array', $collection_array);
    }
    
    public function addAction()
    {
        $results_check = $_POST['results_check_box'];
        $collection = $_POST['digitalNZ_collection'];
	
        // Asserts Item was Checked and Redirects is In-Valid 
        if (!$results_check[0]) {
            $this->_helper->redirector->goto('index', 'index', null, array('message' => 'error'));
        }
		
        $collection_id = '';
		
        // New Collection Created and Retrieved from DB so Items can be Added Based on ID 
        if ($collection == "exist_collection" && $_POST['digitalNZ_collection_select'] != 'Please select a collection') {
            $collection_id = $_POST['digitalNZ_collection_select'];		
        } else if ($collection == "new_collection" && $_POST['new_collection']!= 'Please input a name') {
            $collection = insert_collection(array('name'=> $_POST['new_collection'], 'public'=>true, 'description'=>' '));				
            $collectionTable = get_db()->getTable('Collection');
            $select = $collectionTable->getSelect();
            $select->where('name = ?', $_POST['new_collection']);
            $collection_id = $collectionTable->fetchObject($select)->id;		
        }		
				
        // Each Item Selected By the User is Comitted to an Omeka Item 
        foreach ($results_check as $result) {
            $this->_createItem($result, $collection_id);
        }
		
	// Item's Successfully Added and User is Redirected with Success Message 
	$this->_helper->redirector->goto('index', 'index', null, array('message' => 'success'));
    }
    
    /**
     *  Updates items older than 30 days to comply with Digital NZ terms of use
     */
    public function refreshAction()
    {	
        $itemTable = get_db()->getTable('DigitalNZItem');
        $overdueItems = $itemTable->findOverdue();
		
        foreach ($overdueItems as $overdue) {
            $item = get_item_by_id($overdue->item_id);
            $item->deleteElementTexts(); 
			
            // JSON Web Service Request Made to DNZ */
            $dnzItem = $this->_searchForItem($overdue->dnz_id);
                
            $item->deleteElementTexts(); 
                
            // User Selection to Use Dublin-Core MetaData Standard */
            if ($overdue->is_dublin) {
                update_item($item, array('public' => true), array('Dublin Core'=> $this->_formatDC($dnzItem)));	
            } else { 
                update_item($item, array('public' => true), array('Digital New Zealand' => $this->_formatDnz($dnzItem)));
            }   
                        
            $itemTable->find($overdue->id)->UpdateDateAdded();
        }
	
        $this->view->assign('overdue_items', $overdueItems);
    }
	
    /**
     *  @param $result - DNZ Item Specified by User, $collection_id - 
     */
    public function _createItem($result, $collection_id)
    {
        // JSON Web Service Request Made to DNZ */ $result
        $dnzItem = $this->_searchForItem($result);
		
        $importItem = new DigitalNZItem();
			
        // User Selection to Use Dublin-Core MetaData Standard */
        if (get_option("use_dublin_core")) {
            $item = insert_item(array('public' => true, 'collection_id' => $collection_id), array('Dublin Core' => $this->_formatDC($dnzItem))); 
            $importItem->is_dublin = 1;
        } else { 
            $item = insert_item(array('public' => true, 'collection_id' => $collection_id), array('Digital New Zealand' => $this->_formatDnz($dnzItem)));
            $importItem->is_dublin = 0; //Remove this and make default entry 0 in DB?
        }
		
        $importItem->item_id = $item->id;
        $importItem->collection_id = $collection_id;
        $importItem->dnz_id = $dnzItem['id'];
        $importItem->added = date("Y-m-d");
        $importItem->save();
        release_object($importItem);
			
        // File Content is Inserted *
        try {
            if($dnzItem['object_url']) {
                $url = $dnzItem['object_url'];
            } else {
                $url = $dnzItem['large_thumbnail_url'];
            }
			
            $file = insert_files_for_item($item,  'Url', array($url));
            release_object($file);
        } catch (Exception $e) {
			
        }	
    }
	
    /** 
     *  Returns formatted Digital New Zealand JSON Item 
     *
     *  @param ID of DNZ item
     */
    public function _searchForItem($dnzId)
    {
        $url = 'http://api.digitalnz.org/records/v2.json?search_text=id:"' . $dnzId . '"&api_key=6y98irEtPSynyEbqTPfw';

        $dnzResult = json_decode(file_get_contents($url), true); 
		
        return $dnzResult['results'][0];
    }
	
    /**
     *  DigitalNZ item formatted according to Dublin-Core standard 
     *
     *  @param array 
     */
    public function _formatDC($dnzItem)
    {
        $dublinCore = array(
            'Title'       => array(array('text' => $dnzItem['title'], 'html' => false)),
            'Description' => array(array('text' => $dnzItem['description'], 'html' => false)),
            'Creator'     => array(array('text' => $dnzItem['author'], 'html' => false)),
            'Source'      => array(array('text' => $dnzItem['content_provider'], 'html' => false)),
            'Publisher'   => array(array('text' => $dnzItem['publisher'], 'html' => false)),
            'Date'        => array(array('text' => $dnzItem['date'], 'html' => false)),
            'Rights'      => array(array('text' => $dnzItem['object_copyright'], 'html' => false)),
            'Format'      => array(array('text' => $dnzItem['dctype'], 'html' => false))
        );
		
        return $dublinCore;
    }
	
    /**
     * DigitalNZ MetaData standard retained. Formatted as ElementTexts Array
     *
     * @param array
     */
    public function _formatDnz($dnzItem)
    {
        $digitalNZ = array();
		
        foreach($dnzItem as $key => $value) {
            $digitalNZ[$key] = array(array('text' => $value, 'html' => false));
        }
		
        return $digitalNZ;
    }
}
