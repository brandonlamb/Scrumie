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

    protected $history;

    public function _init() {
        $this->history = new Relation('TaskHistory', array('id_task'=>'id'));
    }

    public function __set_state($value) {
        if(!in_array($value, self::$availablesStates))
            throw new InvalidArgumentException("Invalid state for task $value");

        $this->data['state'] = $value;
    }

    public function isTodo() {
        return ($this->state == self::STATE_TODO) ? true : false;
    }


    public function isInProgress() {
        return ($this->state == self::STATE_INPROGRESS) ? true : false;
    }

    public function isCommited() {
        return ($this->state == self::STATE_COMMITED) ? true : false;
    }

    public function isReadyForTest() {
        return ($this->state == self::STATE_READYFORTEST) ? true : false;
    }

    public function isDone() {
        return ($this->state == self::STATE_DONE) ? true : false;
    }

    public function isDetached() {
        return ($this->state == self::STATE_DETACHED) ? true : false;
    }

    public function getHistory() {
        return TaskHistory::fetchBy('task_id', $this->id);
    }

    public function save() {
        $result = parent::save();
        $this->saveStateToHistory();
        return $result;
    }

    public function saveStateToHistory() {
        $today = date('Y-m-d 00:00:00', time());
        $taskHistory = TaskHistory::getIfExistsOrCreateNewOne($this->id, $today);
        $taskHistory->done = $this->done;
        $taskHistory->save();
    }
}
