<?php

require_once(PHP_RABBIT_PATH.'core/Api.php');
class AppControllerException extends Exception {}
class AppController extends Controller
{
    public function getApi($name) {
        require_once(APP_PATH . "api/${name}Api.php");
        return Api::getApi($name);
    }

    public function getCurrentProjectId() {
        if (! isset($_SESSION['projectId']) )
            return false;

        return (int) $_SESSION['projectId'];
    }

    public function isLogged() {
        return (bool) $this->getCurrentProjectId();
    }
}
