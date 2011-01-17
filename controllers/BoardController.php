<?php

class BoardControllerException extends ScrumieControllerException {}
class BoardController extends ScrumieController {

    public $layout = 'logged.phtml';

    public function indexAction() {
        $sprintId = $this->_getParam('sprint');

        if(!$this->getCurrentProjectId())
            throw new BoardControllerException('Unathorize access');

        $this->view->sprints = $this->getApi('Sprint')->fetchAllForProjectId($this->getCurrentProjectId());

        $this->view->tasks = $this->getApi('Task')->fetchTaskForSprint($sprintId);
        $this->view->detached = $this->getApi('Task')->fetchDetached($this->getCurrentProjectId());
        $this->view->sprintName = ($sprintId) ? $this->getApi('Sprint')->getById($sprintId)->name : 'not set';
        $this->view->btc_y_max = ($sprintId) ? $this->getApi('Sprint')->getSprintEstimation($sprintId) + 1 : 0;
        $this->view->btc_series = ($sprintId) ? join(',',$this->getApi('Sprint')->getEstimationForEachSprintDate($sprintId)) : 0;

        $updates = array();
        if($sprintId) {
        foreach($this->getApi('Sprint')->getSprintUpdateDates($sprintId) as $date)
            $updates[] = date('D/d/M', strtotime($date));
        }

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
        $projectId = $this->getCurrentProjectId();

        $task = $this->getApi('Task')->saveTask($sprintId, $taskId, $body, $estimation, $owner, $state, $done, $projectId);

        $this->result = $task->getId();
    }

    public function addNewSprintAction() {
        $sprintName = $this->_getParam('sprintName');
        $projectId = $this->getCurrentProjectId();
        $sprint = $this->getApi('Sprint')->addNewSprint($sprintName, $projectId);
        $this->result = $sprint->getId();
    }

    public function deleteTaskAction() {
        $taskId = $this->_getParam('taskId');
        $this->getApi('Task')->deleteTask($taskId);
        $this->result = true;
    }

    public function reorderTaskAction() {
        $order = $this->_getParam('order');
        $this->getApi('Task')->reorderTask($order);
    }
}
