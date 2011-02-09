<?php

class ProjectController extends ScrumieController 
{
    public function registryAction() {
       $service = $this->getApi('Project');
       $service->registry($this->getParam('name'), $this->getParam('password'));
       $this->result = true;
    }

    public function loginAction() {
        $name = $this->getParam('name');
        $password = $this->getParam('password');

        if(! $projectId = $this->getApi('Project')->authorize($name, $password))
            throw new Exception('Invalid name or password');

        $_SESSION['projectId'] = $projectId;

        $this->result = true;
    }

    public function logoutAction() {
        session_destroy();
        $this->redirect('Index', 'index');
    }
}
