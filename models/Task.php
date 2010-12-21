<?php

require_once('DataModel.php');

class Task extends DataModel 
{
    const STATE_TODO = 'todo';
    const STATE_INPROGRESS = 'inProgress';
    const STATE_COMMITED = 'commited';
    const STATE_READYFORTEST = 'readyForTest';
    const STATE_DONE = 'done';

    static public $availablesStates = array(
        self::STATE_TODO,
        self::STATE_INPROGRESS,
        self::STATE_COMMITED,
        self::STATE_READYFORTEST,
        self::STATE_DONE
    );

    protected $data = array(
        'id_task' => null,
        'body' => null,
        'estimation' => null,
        'id_sprint' => null,
        'owner' => null,
        'state' => null,
    );

    const _CLASS_ = __CLASS__;
    const TABLE = 'task';
    const INDEX = 'id_task';

    static public function fetchBySprintId($sprintId) {
        return self::fetchBy('id_sprint', $sprintId);
    }

    public function __set_state($value) {
        if(!in_array($value, self::$availablesStates))
            throw new InvalidArgumentException('Invalid state for task');

        $this->data['state'] = $value;
    }
}
