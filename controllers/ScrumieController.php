<?php

require_once ('./core/Api.php');

class ScrumieControllerException extends Exception {}
class ScrumieController extends Controller
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
