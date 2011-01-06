<?php

require_once('DataModel.php');

class TaskHistory extends DataModel 
{
    protected $data = array(
        'id' => null,
        'id_task' => null,
        'date' => null,
        'done' => null,
    );

    const _CLASS_ = __CLASS__;
    const TABLE = 'task_history';
    const INDEX = 'id';

    static public function getIfExistsOrCreateNewOne($taskId, $date) {
        $result = self::fetchByColumns(array('id_task' => $taskId, 'date' => $date));

        if($result)
            $taskHistory = $result[0];
        else
            $taskHistory = new self;

        $taskHistory->id_task = $taskId;
        $taskHistory->date = $date;

        return $taskHistory;
    }
}
