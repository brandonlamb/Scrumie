<?php

require_once 'Request.php';

class FrontController {
    protected $Request;
    protected $controlers_dir = './controllers/';

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

        $ControllerCalled = new $controller_name($action);

        if(!$ControllerCalled instanceof Controller)
            throw new Exception(sprintf('Controller %s must be instanceof Controller', $controller_name));

        $ControllerCalled->$action_name();

        $ControllerCalled->flush($action);
    }
}
