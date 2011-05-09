<?php

class BoardControllerException extends AppControllerException {}
class BoardController extends AppController {

    public $layout = 'logged.phtml';

    public function indexAction() {
        $sprintId = $this->getParam('sprint');

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
        $projectId = $this->getCurrentProjectId();
        $sprintId = $this->getParam('sprintId');
        $taskId = $this->getParam('taskId');
        $body = $this->getParam('body');
        $estimation = $this->getParam('estimation');
        $owner = $this->getParam('owner');
        $state = $this->getParam('state');
        $done = $this->getParam('done');

        $task = $this->getApi('Task')->saveTask($sprintId, $taskId, $body, $estimation, $owner, $state, $done, $projectId);

        $this->result = $task->getId();
    }

    public function addNewSprintAction() {
        $sprintName = $this->getParam('sprintName');
        $projectId = $this->getCurrentProjectId();
        $sprint = $this->getApi('Sprint')->addNewSprint($sprintName, $projectId);
        $this->result = $sprint->getId();
    }

    public function deleteTaskAction() {
        $taskId = $this->getParam('taskId');
        $this->getApi('Task')->deleteTask($taskId);
        $this->result = true;
    }

    public function reorderTaskAction() {
        $order = $this->getParam('order');
        $this->getApi('Task')->reorderTask($order);
    }

    public function deleteSprintAction() {
        if($this->getApi('Sprint')->getById($this->getParam('id'))->id_project != $this->getCurrentProjectId())
            throw new BoardControllerException(sprintf('You can delete sprint only from current selected projects'));
        $this->getApi('Sprint')->deleteSprint($this->getParam('id'));
        $this->result = true;
    }

    public function renameSprintAction() {
        if($this->getApi('Sprint')->getById($this->getParam('id'))->id_project != $this->getCurrentProjectId())
            throw new BoardControllerException(sprintf('You can rename sprint only from current selected projects'));
        $this->getApi('Sprint')->renameSprint($this->getParam('id'), $this->getParam('name'));
        $this->result = true;
    }
}
