<?php

require_once 'Request.php';

class FrontController {
    public $Routing;
    public $controlers_dir = './controller/';
    static protected $instance;

    protected function __construct() {
    }

    static public function getInstance() {
        if (! self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function setRouting($routingName) {
        $routing = new $routingName;
        if(! $routing instanceof RoutingInterface)
            throw new InvalidArgumentException(sprintf('Class %s must implements RoutingInterface', get_class($routing)));
        $this->Routing = $routing;
        return $this;
    }

    public function dispatch() {
        $controller_name = $this->Routing->getControllerName();
        $action_name = $this->Routing->getActionName();

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
            $ErrorController->indexAction($e);
            $ErrorController->flush();
        }

    }
}
