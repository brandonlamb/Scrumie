<?php

require_once ('./models/Task.php');

class TaskService extends Service
{
    public function fetchTaskForSprint($sprintId) {
        return array(
            'todo' => array(),
            'inProgress' => array(),
            'commited' => array(),
            'readyForTest' => array(),
            'done' => array(),
        );
    }

    public function saveTask($sprintId, $taskId, $body, $estimation, $owner) {
        $task = new Task();
        $task->id_sprint = $sprintId;
        $task->id_task = $taskId;
        $task->body = $body;
        $task->estimation = $estimation;
        $task->owner = $owner;

        if($taskId)
            $task->update();
        else
            $task->id_task = $task->insert();

        return $task;
    }
}
