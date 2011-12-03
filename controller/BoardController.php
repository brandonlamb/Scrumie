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
        if(!User::hasProjectWithId($this->getCurrentUser()->id, $projectId)) {
            throw new AppControllerException('Project dosen\'t belong to user');
        }
        Session::set('project', $projectId);
        $this->view->project = Project::getById($projectId);
        $this->view->currentProjectId = $this->getCurrentProjectId();
        $this->view->setTemplateFile('index.phtml');
    }

    /*
    public function _indexAction() {
        $this->view->btc_series = ($sprintId) ? join(',',$this->getApi('Sprint')->getEstimationForEachSprintDate($sprintId)) : 0;
    }
    */
}
