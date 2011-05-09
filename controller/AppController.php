<?php

class AppControllerException extends Exception {}
class AppController extends Controller
{
    public function getApi($name) {
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
