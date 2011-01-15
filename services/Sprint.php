<?php

require_once ('./models/Sprint.php');
require_once ('./models/Task.php');
require_once ('./models/TaskHistory.php');

class SprintService extends Service
{
    public function fetchAllForProjectId($projectId) {
        return DAO::get('Sprint')->by('id_project', $projectId);
    }

    public function getById($id) {
        return DAO::get('Sprint')->byId($id);
    }

    public function addNewSprint($sprintName, $projectId) {
        $sprint = new Sprint();
        $sprint->name = $sprintName;
        $sprint->startdate = date('Y-m-d H:i:s', time());
        $sprint->id_project = $projectId;
        $sprint->id = DAO::insert($sprint);

        return $sprint;
    }

    public function getAllTaskIdsForSprint($sprintId) {
        $tasks_ids = array();
        foreach(DAO::get('Task')->by('id_sprint', $sprintId) as $task)
            $tasks_ids[] = $task->id;
        return $tasks_ids;
    }

    public function getSprintUpdateDates($sprintId) {
        $tasks_ids = $this->getAllTaskIdsForSprint($sprintId);
        $updateDates = $this->getService('Task')->getTasksUpdateDates($tasks_ids);
        return $updateDates;
    }

    public function getSprintEstimation($sprintId) {
        return DAO::query("select sum(estimation) as sum from task where id_sprint = $sprintId")->pop()->sum;
    }

    public function getEstimationForEachSprintDate($sprintId) {
        $tasks_ids = $this->getAllTaskIdsForSprint($sprintId);
        $result = array();
        foreach($tasks_ids as $id) {
            foreach(DAO::get('TaskHistory')->by('id_task', $id, array('date ASC')) as $data) {
                if(array_key_exists($data->date, $result))
                    $result[$data->date] += $data->done;
                else
                    $result[$data->date] = $data->done;
            }
        }
        
        $estimation = $this->getSprintEstimation($sprintId);

        foreach($result as $date => $done)
            $result[$date] = $estimation - $done;

        return $result;
    }
}
