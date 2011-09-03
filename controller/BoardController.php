<?php

class BoardControllerException extends AppControllerException {}
class BoardController extends AppController {

    public $layout = 'logged.phtml';


    public function preDispatch() {
        $this->view->user = $this->getCurrentUser();
    }

    public function indexAction() {
    }

    public function projectAction() {
        $projectId = $this->getParam('id', $this->getCurrentProjectId());
        if(!User::hasProjectWithId($this->getCurrentUser()->id, $projectId))
            throw new AppControllerException('Project dosen\'t belong to user');
        Session::set('project', $projectId);
        $this->view->project = Project::getById($projectId);
        $this->view->currentProjectId = $this->getCurrentProjectId();
        $this->view->setTemplateFile('index.phtml');
    }

    public function sprintAction() {
        $this->view->sprint = $sprint = Sprint::getById($this->getParam('id'));
        $this->view->currentProjectId = $this->getCurrentProjectId();
        if($sprint->id_project != $this->getCurrentProjectId())
            throw new AppControllerException('Sprint dosen\'t belong to current project');
        Session::set('sprint', $sprint->id);
        $this->view->user = $this->getCurrentUser();
        $this->view->project = $this->getCurrentProject();
        $this->view->setTemplateFile('index.phtml');
    }

    public function _indexAction() {
        $this->view->btc_series = ($sprintId) ? join(',',$this->getApi('Sprint')->getEstimationForEachSprintDate($sprintId)) : 0;
    }
}
