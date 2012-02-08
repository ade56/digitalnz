<?php
/**
 *
 **/
class DigitalNZItem extends Omeka_Record
{	
	public $id;
	public $item_id;
	public $collection_id;
	public $dnz_id;
	public $is_dublin;
	public $added;
        
        /**
         *
         */
        public function UpdateDateAdded()
        {
             $this->added = date("Y-m-d");  
             $this->save();
        }
        
}