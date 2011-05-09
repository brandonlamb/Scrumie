<?php

require_once 'RoutingInterface.php';
require_once 'Routing.php';

//?controller=...&action=...&param1.....
class SimplePathRouting extends Routing implements RoutingInterface {
    public $controller;
    public $action;

    public function __construct() {
        $uri = $_SERVER['REQUEST_URI'];
        $path = explode('/',$uri);

        if(count($path) == 2 ) {
            $this->controller = ($path[1]) ? ucfirst($path[1]) : 'Index';
            $this->action = 'index';
        }
        elseif(isset($path[1]) && isset($path[2])) {
            $this->controller = ($path[1]) ? ucfirst($path[1]) : 'Index';
            $this->action = ($path[2]) ? $path[2] : 'index';
        }
        else {
            throw new RoutingException('Invalid routing path '.$uri);
        }
    }

    public function getActionName() {
        return $this->action.'Action';
    }

    public function getControllerName() {
        return $this->controller.'Controller';
    }

    public function toString() {
        return sprintf('/%s/%s', $this->controller, $this->action);
    }
}
