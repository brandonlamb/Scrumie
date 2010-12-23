<?php

class UserController extends ScrumieController 
{
    public function registryAction() {
       $serviceUser = $this->getService('User');
       try {
       $serviceUser->registryUser($this->_getParam('login'), $this->_getParam('password'));
       $this->result = true;
       } catch (Exception $e) {
           $this->result = $e->getMessage();
       }
    }

    public function loginAction() {
        $login = $this->_getParam('login');
        $password = $this->_getParam('password');

        if(! $this->getService('User')->authorize($login, $password))
            throw new Exception('Invalid login or password');

        $_SESSION['userLogged'] = $login;

        $this->result = true;
    }
}
