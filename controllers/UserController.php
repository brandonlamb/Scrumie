<?php

class UserController extends ScrumieController 
{
    public function registryAction() {
       $serviceUser = $this->getService('User');

       $serviceUser->registryUser($this->_getParam('email'), $this->_getParam('password'));
       $this->result = array('ok'); 

       $this->_forward('User', 'login');

    }

    public function loginAction() {
        $email = $this->_getParam('email');
        $password = $this->_getParam('password');

        if(! $this->getService('User')->authorize($email, $password))
            throw new Exception('Invalid email or password');

        $_SESSION['userLogged'] = $email;

        $this->result = true;
    }
}
