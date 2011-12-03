<?php

class Task extends DbModel
{
    const TABLE = 'task';
    const INDEX = 'id';

    const STATE_TODO = 'todo';
    const STATE_INPROGRESS = 'inProgress';
    const STATE_TOVERIFY = 'toVerify';
    const STATE_DONE = 'done';
    const STATE_DETACHED = 'detached';

    static public $availablesStates = array(
        self::STATE_TODO,
        self::STATE_INPROGRESS,
        self::STATE_TOVERIFY,
        self::STATE_DONE,
        self::STATE_DETACHED
    );

    protected $data = array(
        'id' => null,
        'body' => null,
        'estimation' => null,
        'owner' => null,
        'state' => null,
        'done' => null,
        'id_project' => null,
        'color' => null,
        'id_story' => null,
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

    public function isToVerify() {
        return ($this->state == self::STATE_TOVERIFY) ? true : false;
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
