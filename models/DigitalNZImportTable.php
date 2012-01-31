<?php

class DigitalNZImportTable extends Omeka_Db_table
{
	public function findOverdue()
	{
		$select = $this->getSelect();
        $select->where('added >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)');
        return $this->fetchObjects($select);
	}	
}


