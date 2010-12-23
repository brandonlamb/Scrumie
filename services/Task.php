<?php

require_once ('./models/Task.php');

class TaskService extends Service
{
    public function fetchTaskForSprint($sprintId) {


        $tasks['todo'] = array(); 
        $tasks['inProgress'] = array(); 
        $tasks['commited'] = array(); 
        $tasks['readyForTest'] = array(); 
        $tasks['done'] = array(); 

        foreach(Task::fetchBySprintId($sprintId) as $task)
            $tasks[$task->state][] = $task;

        return array(
            'todo' => $tasks['todo'],
            'inProgress' => $tasks['inProgress'],
            'commited' => $tasks['commited'],
            'readyForTest' => $tasks['readyForTest'],
            'done' => $tasks['done'],
        );
    }

    public function saveTask($sprintId, $taskId, $body, $estimation, $owner, $state, $done) {
        $task = new Task();
        $task->id_sprint = $sprintId;
        $task->id_task = $taskId;
        $task->body = $body;
        $task->estimation = $estimation;
        $task->owner = $owner;
        $task->state = $state;
        $task->done = $done;

        if($taskId)
            $task->update();
        else
            $task->id_task = $task->insert();

        return $task;
    }

    public function deleteTask($taskId) {
        Task::getById($taskId)->delete();
    }
}
