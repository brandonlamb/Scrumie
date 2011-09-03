<?php

class Sprint extends DbModel
{
    protected $data = array(
        'id' => null,
        'name' => null,
        'startdate' => null,
        'id_project' => null,
    );

    const TABLE = 'sprint';
    const INDEX = 'id';

    protected $tasks;

    public function _init() {
        $this->tasks = new Relation('Task', array('id_sprint'=>'id'));
    }

    public function getTasks() {
        if(!$this->tasks) {
            $this->tasks = parent::__get('tasks');
        }

        return $this->tasks;
    }

    public function getSprintEstimation() {
        return $this->getTasks()->getEstimationSum();
    }

    public function getEstimationForEachSprintDate() {
        $tasks = $this->getTasks();

        $dateEstimation = array();

        //foreach update of task progress
        foreach($tasks->getAllTasksUpdateDates() as $key => $date) {
            //take each task from sprint
            foreach($tasks as $task) {
                //check if it has history in that date
                foreach($task->history as $history) {
                    if($date == $history->date) {
                        //calculate done progress for this date
                        if(isset($dateEstimation[$date]))
                            $dateEstimation[$date] += $history->done;
                        else
                            $dateEstimation[$date] = $history->done;
                    }
                }
            }
        }

        $sprintEstimation = $this->getSprintEstimation();
        foreach($dateEstimation as $id => $estimation) {
            $dateEstimation[$id] = $sprintEstimation - $estimation;
        }

        return $dateEstimation;
    }
}
