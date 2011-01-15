<?php

class BoardControllerException extends ScrumieControllerException {}
class BoardController extends ScrumieController {

    protected $layout = 'logged.phtml';

    public function getCurrentProjectId() {
        return (int) $_SESSION['projectId'];
    }

    public function indexAction() {
        $sprintId = $this->_getParam('sprint');

        if(!$this->getCurrentProjectId())
            throw new BoardControllerException('Unathorize access');

        $this->view->sprints = $this->getService('Sprint')->fetchAllForProjectId($this->getCurrentProjectId());

        $this->view->tasks = $this->getService('Task')->fetchTaskForSprint($sprintId);
        $this->view->detached = $this->getService('Task')->fetchDetached($this->getCurrentProjectId());
        $this->view->sprintName = ($sprintId) ? $this->getService('Sprint')->getById($sprintId)->name : 'not set';
        $this->view->btc_y_max = ($sprintId) ? $this->getService('Sprint')->getSprintEstimation($sprintId) + 1 : 0;
        $this->view->btc_series = ($sprintId) ? join(',',$this->getService('Sprint')->getEstimationForEachSprintDate($sprintId)) : 0;

        $updates = array();
        if($sprintId) {
        foreach($this->getService('Sprint')->getSprintUpdateDates($sprintId) as $date)
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

        $task = $this->getService('Task')->saveTask($sprintId, $taskId, $body, $estimation, $owner, $state, $done, $projectId);

        $this->result = $task->getId();
    }

    public function addNewSprintAction() {
        $sprintName = $this->_getParam('sprintName');
        $projectId = $this->getCurrentProjectId();
        $sprint = $this->getService('Sprint')->addNewSprint($sprintName, $projectId);
        $this->result = $sprint->getId();
    }


    /** not ready **/
    public function reorderTaskAction() {
        $order = $this->_getParam('order');

        $this->getService('Task')->reorderTask($order);
    }

    public function deleteTaskAction() {
        $taskId = $this->_getParam('taskId');

        $this->getService('Task')->deleteTask($taskId);

        $this->result = true;
    }

}
