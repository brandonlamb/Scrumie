<?php
class TaskHistory extends DbModel
{
    protected $data = array(
        'id' => null,
        'id_task' => null,
        'date' => null,
        'done' => null,
    );

    const TABLE = 'task_history';
    const INDEX = 'id';

    static public function getIfExistsOrCreateNewOne($taskId, $date) {
        $result = self::getBy(array('id_task' => $taskId, 'date'=>$date));

        $result = DAO::get(__CLASS__)->by(array('id_task' => $taskId, 'date' => $date));

        if($result->count())
            $taskHistory = current($result);
        else
            $taskHistory = new self;

        $taskHistory->id_task = $taskId;
        $taskHistory->date = $date;

        return $taskHistory;
    }
}
