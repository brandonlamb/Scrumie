<?php

class SprintApi extends Api
{
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
