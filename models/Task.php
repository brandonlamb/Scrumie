<?php

class Task extends DbModel
{
    const TABLE = 'task';
    const INDEX = 'id';

    const STATE_TODO = 'todo';
    const STATE_INPROGRESS = 'inProgress';
    const STATE_COMMITED = 'commited';
    const STATE_READYFORTEST = 'readyForTest';
    const STATE_DONE = 'done';
    const STATE_DETACHED = 'detached';

    static public $availablesStates = array(
        self::STATE_TODO,
        self::STATE_INPROGRESS,
        self::STATE_COMMITED,
        self::STATE_READYFORTEST,
        self::STATE_DONE,
        self::STATE_DETACHED
    );

    protected $data = array(
        'id' => null,
        'body' => null,
        'estimation' => null,
        'id_sprint' => null,
        'owner' => null,
        'state' => null,
        'done' => null,
        'order' => null,
        'id_project' => null,
    );

    public function __set_state($value) {
        if(!in_array($value, self::$availablesStates))
            throw new InvalidArgumentException("Invalid state for task $value");

        $this->data['state'] = $value;
    }

    public function getHistory() {
        return TaskHistory::fetchBy('task_id', $this->id);
    }
}
