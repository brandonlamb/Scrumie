<?php

class BoardController extends ScrumieController {

    protected $layout = 'logged.phtml';

    public function indexAction() {
        $sprintId = $this->_getParam('sprint');

        $this->view->sprints = $this->getService('Sprint')->fetchAll();
        $this->view->tasks = $this->getService('Task')->fetchTaskForSprint($sprintId);
        $this->view->sprintName = ($sprintId) ? $this->getService('Sprint')->getById($sprintId)->name : '-not selected-';

        $this->view->btc_y_max = $this->getService('Sprint')->getSprintEstimation($sprintId) + 1;
        $this->view->btc_series = join(',',$this->getService('Sprint')->getEstimationForEachSprintDate($sprintId));

        $updates = array();
        foreach($this->getService('Sprint')->getSprintUpdateDates($sprintId) as $date)
            $updates[] = date('D/d/M', strtotime($date));

        $this->view->btc_x_categories = '"'.join('","', $updates).'"';
    }

    public function saveTaskAction() {
        $sprintId = $this->_getParam('sprintId');
        $taskId = $this->_getParam('taskId');
        $body = $this->_getParam('body');
        $estimation = $this->_getParam('estimation');
        $owner = $this->_getParam('owner');
        $state = $this->_getParam('state');
        $done = $this->_getParam('done');

        $task = $this->getService('Task')->saveTask($sprintId, $taskId, $body, $estimation, $owner, $state, $done);

        $this->result = $task->getId();
    }

    public function deleteTaskAction() {
        $taskId = $this->_getParam('taskId');

        $this->getService('Task')->deleteTask($taskId);

        $this->result = true;
    }

    public function addNewSprintAction() {
        $sprintName = $this->_getParam('sprintName');
        $sprint = $this->getService('Sprint')->addNewSprint($sprintName);
        $this->result = $sprint->getId();
    }
}
