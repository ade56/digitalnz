<?php

require_once 'DigitalNZItem.php';
require_once 'Omeka/Filter/Filename.php';
require_once 'Zend/Uri.php';

class DigitalNZ_ImportProcess extends ProcessAbstract
{

    public function run($args)
    {
        error_log('run function coming in here',0);
    }
}