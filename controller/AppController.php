<?php

require_once(PHP_RABBIT_PATH.'core/Controller.php');
class AppControllerException extends Exception {}
class AppController extends Controller
{
    public function isLogged() {
        return (Session::get('login')) ? true : false;
    }

    public function getCurrentUser() {
        $user = User::getBy(array('login'=>Session::get('login')));
        if(! $user->count()) {
            throw new AppControllerException('Not logged');
        }
        return current($user);
    }

    public function getCurrentProjectId() {
        return Session::get('project');
    }

    public function getCurrentSprintId() {
        return Session::get('sprint');
    }

    public function getCurrentProject() {
        return Project::getById($this->getCurrentProjectId());
    }
}
