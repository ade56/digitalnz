<?php

/**
 * Represents a digitalnz_import_items record object.
 */
class DigitalNZItem extends Omeka_Record
{	
	public $id;
	public $item_id;
	public $collection_id;
	public $dnz_id;
	public $is_dublin;
	public $added;
        
        /**
         * Items added date is updated following refresh action
         */
        public function UpdateDateAdded()
        {
             $this->added = date("Y-m-d");  
             $this->save();
        }
        
}