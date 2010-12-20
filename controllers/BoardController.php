<?php

class BoardController extends ScrumieController {

    protected $layout = 'logged.phtml';

    public function indexAction() {
        $sprintId = $this->_getParam('sprint');

        $this->view->sprints = $this->getService('Sprint')->fetchAll();
        $this->view->tasks = ($sprintId) ? $this->getService('Task')->fetchTaskForSprint($sprintId) : array();
        $this->view->sprintName = ($sprintId) ? $this->getService('Sprint')->getById($sprintId)->name : 'NoName';
    }

    public function saveTaskAction() {
        $sprintId = $this->_getParam('sprintId');
        $taskId = $this->_getParam('taskId');
        $body = $this->_getParam('body');
        $estimation = $this->_getParam('estimation');
        $owner = $this->_getParam('owner');

        $task = $this->getService('Task')->saveTask($sprintId, $taskId, $body, $estimation, $owner);

        $this->result = $task->getId();
    }
}
