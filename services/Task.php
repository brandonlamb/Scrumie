<?php

require_once ('./models/Task.php');
require_once ('./models/TaskHistory.php');

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

        $this->saveToHistory($task);
        
        return $task;
    }

    public function saveToHistory(Task $task) {
        $today = date('Y-m-d 00:00:00', time());
        $taskHistory = TaskHistory::getIfExistsOrCreateNewOne($task->id_task, $today);
        $taskHistory->done = $task->done;

        if($taskHistory->id)
            $taskHistory->update();
        else
            $taskHistory->insert();
    }

    public function deleteTask($taskId) {
        Task::getById($taskId)->delete();
    }
}
