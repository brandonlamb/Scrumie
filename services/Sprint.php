<?php

require_once ('./models/Sprint.php');

class SprintService extends Service
{
    public function fetchAllForProjectId($projectId) {
        return Sprint::fetchBy('id_project', $projectId);
    }

    public function getById($id) {
        return Sprint::getById($id);
    }

    public function addNewSprint($sprintName, $projectId) {
        $sprint = new Sprint();
        $sprint->name = $sprintName;
        $sprint->startdate = date('Y-m-d H:i:s', time());
        $sprint->id_project = $projectId;
        $sprint->id_sprint = $sprint->insert();

        return $sprint;
    }

    public function getAllTaskIdsForSprint($sprintId) {
        $tasks_ids = array();
        foreach(DataModel::fetch("select id_task from task where id_sprint = $sprintId") as $data)
            $tasks_ids[] = $data->id_task;
        return $tasks_ids;
    }

    public function getSprintUpdateDates($sprintId) {
        $tasks_ids = $this->getAllTaskIdsForSprint($sprintId);
        $updateDates = $this->getService('Task')->getTasksUpdateDates($tasks_ids);
        return $updateDates;
    }

    public function getSprintEstimation($sprintId) {
        return DataModel::fetchOne("select sum(estimation) as sum from task where id_sprint = $sprintId", 'sum');
    }

    public function getEstimationForEachSprintDate($sprintId) {
        $tasks_ids = $this->getAllTaskIdsForSprint($sprintId);
        $result = array();
        foreach($tasks_ids as $id) {
            foreach(DataModel::fetch("select date, done from task_history where id_task = $id order by date asc") as $data) {
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
