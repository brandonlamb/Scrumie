<?php

class ProjectController extends ScrumieController 
{
    public function registryAction() {
       $service = $this->getService('Project');
       $service->registry($this->_getParam('name'), $this->_getParam('password'));
       $this->result = true;
    }

    public function loginAction() {
        $name = $this->_getParam('name');
        $password = $this->_getParam('password');

        if(! $projectId = $this->getService('Project')->authorize($name, $password))
            throw new Exception('Invalid name or password');

        $_SESSION['projectId'] = $projectId;

        $this->result = true;
    }

    public function logoutAction() {
        session_destroy();
        $this->_redirect('Index', 'index');
    }
}
