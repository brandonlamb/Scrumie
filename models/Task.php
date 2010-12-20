<?php

require_once('DataModel.php');

class Task extends DataModel 
{
    protected $data = array(
        'id_task' => null,
        'body' => null,
        'estimation' => null,
        'id_sprint' => null,
        'owner' => null,
    );

    const _CLASS_ = __CLASS__;
    const TABLE = 'task';
    const INDEX = 'id_task';
}
