<?php

require_once 'RoutingInterface.php';
require_once 'Request.php';
require_once 'Routing.php';

//?controller=...&action=...&param1.....
class BaseRouting extends Routing implements RoutingInterface {
    public function __construct() {
        $this->action = Request::getInstance()->getParam('action', 'index');
        $this->controller = Request::getInstance()->getParam('controller', 'Index');
    }

    public function getActionName() {
        return $this->action.'Action';
    }

    public function getControllerName() {
        return $this->controller.'Controller';
    }

    public function toString() {
        return sprintf('?controller=%s&action=%s', $this->controller, $this->action);
    }
}
