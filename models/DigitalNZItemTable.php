<?php

class DigitalNZItemTable extends Omeka_Db_Table
{
	public function findOverdue()
    {
		$select = $this->getSelect();
	    $select->where('added <= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)');
	    return $this->fetchObjects($select);
    }
}
