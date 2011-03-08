<?php

require_once ('./models/Sprint.php');
require_once ('./models/Task.php');
require_once ('./models/TaskHistory.php');

class SprintApi extends Api
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

    public function renameSprint($sprintId, $name) {
        $sprint = $this->getById($sprintId);
        $sprint->name = $name;
        DAO::update($sprint);
    }

    public function deleteSprint($sprintId) {
        DAO::delete('Sprint', $sprintId);
    }

    public function getAllTaskIdsForSprint($sprintId) {
        $tasks_ids = array();
        foreach(DAO::get('Task')->by('id_sprint', $sprintId) as $task)
            $tasks_ids[] = $task->id;
        return $tasks_ids;
    }

    public function getSprintUpdateDates($sprintId) {
        $tasks_ids = $this->getAllTaskIdsForSprint($sprintId);
        $updateDates = $this->getApi('Task')->getTasksUpdateDates($tasks_ids);
        return $updateDates;
    }

    public function getSprintEstimation($sprintId) {
        return DAO::query("select sum(estimation) as sum from task where id_sprint = $sprintId")->pop()->sum;
    }

    public function getEstimationForEachSprintDate($sprintId) {
        $tasks_ids = $this->getAllTaskIdsForSprint($sprintId);
        $updateDates = $this->getSprintUpdateDates($sprintId);
        $sprintEstimation = $this->getSprintEstimation($sprintId);

        $estimation = array();
        foreach($updateDates as $index => $date) {

            foreach($tasks_ids as $id) {
                if($done = DAO::query("select done from task_history where id_task = $id and date = '$date'")->pop())
                    $estimation[$index][$id] = $done->done;
                else
                    $estimation[$index][$id] = ($index) ? $estimation[$index-1][$id] : 0;
            }
        }

        $result = array();
        foreach($estimation as $index => $values) {
            $result[$index] = $sprintEstimation - array_sum($values);
        }

        return $result;
    }
}
