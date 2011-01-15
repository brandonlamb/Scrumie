<?php

require_once ('./models/Task.php');
require_once ('./models/TaskHistory.php');

class TaskService extends Service
{
    public function fetchTaskForSprint($sprintId) {
        //xxx move this to controller
        $tasks['todo'] = array(); 
        $tasks['inProgress'] = array(); 
        $tasks['commited'] = array(); 
        $tasks['readyForTest'] = array(); 
        $tasks['done'] = array(); 

        if(! $sprintId)
            return $tasks;

        foreach(DAO::get('Task')->by('id_sprint', $sprintId) as $task)
            $tasks[$task->state][] = $task;

        return array(
            'todo' => $tasks['todo'],
            'inProgress' => $tasks['inProgress'],
            'commited' => $tasks['commited'],
            'readyForTest' => $tasks['readyForTest'],
            'done' => $tasks['done']
        );
    }

    public function fetchDetached($projectId) {
        return DAO::get('Task')->fetchBy(array('state' => Task::STATE_DETACHED, 'id_project' => $projectId), array('"order" ASC'));
    }


    public function reorderTask(array $order) {
        foreach($order as $index => $task_id)
            DAO::query("UPDATE task SET \"order\" = $index WHERE id = $task_id");
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
            DAO::update($task);
        else
            $task->id = DAO::insert($task);

        $this->saveToHistory($task);
        
        return $task;
    }

    public function saveToHistory(Task $task) {
        $today = date('Y-m-d 00:00:00', time());
        $taskHistory = TaskHistory::getIfExistsOrCreateNewOne($task->id, $today);
        $taskHistory->done = $task->done;

        if($taskHistory->id)
            DAO::update($taskHistory);
        else
            DAO::insert($taskHistory);
    }

    public function deleteTask($taskId) {
        DAO::delete('Task', $taskId);
        $this->deleteTaskHistory($taskId);
    }

    public function deleteTaskHistory($taskId) {
        DAO::query("DELETE FROM task_history WHERE id_task = $taskId");
    }

    public function getTasksUpdateDates(array $tasks_ids) {
        $dates = array();
        foreach(DAO::get('TaskHistory')->by('id_task', $tasks_ids, array('date ASC')) as $data)
            $dates[] = $data->date;
        return $dates;
    }
}
