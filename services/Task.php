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

        if(! $sprintId)
            return $tasks;

        foreach(Task::fetchBySprintId($sprintId) as $task) {
            $tasks[$task->state][] = $task;
        }

        return array(
            'todo' => $tasks['todo'],
            'inProgress' => $tasks['inProgress'],
            'commited' => $tasks['commited'],
            'readyForTest' => $tasks['readyForTest'],
            'done' => $tasks['done']
        );
    }

    public function fetchDetached($projectId) {
        return Task::fetchDetached($projectId);
    }

    public function reorderTask(array $order) {
        foreach($order as $index => $task_id)
            DataModel::query("UPDATE task SET \"order\" = $index WHERE id = $task_id");
    }

    public function saveTask($sprintId, $taskId, $body, $estimation, $owner, $state, $done, $projectId) {
        $task = new Task();
        $task->id = $taskId;
        $task->body = $body;
        $task->estimation = $estimation;
        $task->owner = $owner;
        $task->state = $state;
        $task->done = $done;
        $task->id_project = $projectId;
        $task->id_sprint = ($state == Task::STATE_DETACHED) ? null : $sprintId;

        if($taskId) 
            $task->update();
        else
            $task->id = $task->insert();

        $this->saveToHistory($task);
        
        return $task;
    }

    public function saveToHistory(Task $task) {
        $today = date('Y-m-d 00:00:00', time());
        $taskHistory = TaskHistory::getIfExistsOrCreateNewOne($task->id, $today);
        $taskHistory->done = $task->done;

        if($taskHistory->id)
            $taskHistory->update();
        else
            $taskHistory->insert();
    }

    public function deleteTask($taskId) {
        Task::getById($taskId)->delete();
        $this->deleteTaskHistory($taskId);
    }

    public function deleteTaskHistory($taskId) {
        DataModel::query("DELETE FROM task_history WHERE id_task = $taskId");
    }

    public function getTasksUpdateDates(array $tasks_ids) {
        $dates = array();
        foreach(DataModel::fetch(sprintf("select distinct date from task_history where id_task in (%s) order by date asc",join(',',$tasks_ids))) as $data)
            $dates[] = $data->date;
        return $dates;
    }
}
