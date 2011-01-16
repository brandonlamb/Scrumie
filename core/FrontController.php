<?php

require_once 'Request.php';

class FrontController {
    public $Request;
    public $controlers_dir = './controllers/';

    public function __construct() {
        $this->Request = new Request;
    }

    public function getControllerName() {
        return $this->Request->getParam('controller', 'Index');
    }

    public function getActionName() {
        return $this->Request->getParam('action', 'index');
    }

    public function dispatch($controller, $action) {

        $controller_name = $controller.'Controller';
        $action_name = $action.'Action';

        require_once($this->controlers_dir.$controller_name.'.php');

        $ControllerCalled = new $controller_name();

        if(!$ControllerCalled instanceof Controller)
            throw new Exception(sprintf('Controller %s must be instanceof Controller', $controller_name));

        try {
            $ControllerCalled->calledMethod = $action_name;
            $ControllerCalled->preDispatch();
            $ControllerCalled->$action_name();
            $ControllerCalled->postDispatch();
            $ControllerCalled->flush();
        } catch (Exception $e) {
            require_once($this->controlers_dir.'ErrorController.php');
            $ErrorController = new ErrorController();
            $ErrorController->index($e);
            $ErrorController->flush();
        }

    }
}
