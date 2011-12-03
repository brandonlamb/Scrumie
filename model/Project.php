<?php
class Project extends DbModel
{
    protected $data = array(
        'id' => null,
        'name' => null,
        'password' => null, //depreciated
    );

    const TABLE = 'project';
    const INDEX = 'id';

    public function getTasks() {
        return Task::getBy(array('id_project'=>$this->id));
    }

    public function getUsers() {
        return UserProject::getBy(array('id_project'=>$this->id));
    }

    static public function deleteIfNoContributors(Project $project) {
        $collection = UserProject::getBy('id_project', $project->id);
        if(count($collection) == 0) {
            $project->delete();
        }
    }

    public function getDetachedUserStories() {
        return Story::getBy(array('id_project'=>$this->id, 'active'=>false));
    }

    public function getActiveUserStories() {
        return Story::getBy(array('id_project'=>$this->id, 'active'=>true));
    }

    public function getEstimationForEachDate() {
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

        $sprintEstimation = $this->getTasks()->getEstimationSum();
        foreach($dateEstimation as $id => $estimation) {
            $dateEstimation[$id] = $sprintEstimation - $estimation;
        }

        return $dateEstimation;
    }
}
