<?php

class UserController extends AppController {
    public function registryAction() {
        if($this->getParam('password') != $this->getParam('password2')) {
            throw new AppControllerException('Password mismatch');
        }

        User::registry($this->getParam('login'), $this->getParam('password'));
        $this->result = true;
    }

    public function loginAction() {
        if(!User::authorize($this->getParam('login'), $this->getParam('password'))) {
            throw new AppControllerException('Authorization failed');
        }
        Session::set('login', $this->getParam('login'));
        $this->result = true;
    }

    public function changeEmailAction() {
        $user = $this->getCurrentUser();
        $user->email = $this->getParam('email');
        $user->save();
        $this->result = true;
    }

    public function logoutAction() {
        Session::destroy();
        $this->redirect('Index', 'index');
    }
}
