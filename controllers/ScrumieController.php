<?php

require_once ('./core/Service.php');

class ScrumieControllerException extends Exception {}
class ScrumieController extends Controller
{
    public function getService($name) {
        return Service::getService($name);
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
