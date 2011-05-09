<?php

abstract class Controller {
    public $view;
    public $layout;
    public $result;
    public $calledMethod;
    public $context; //html, json

    final public function __construct() {
        $this->view = new View;
        $this->view->setTemplateFile('index.phtml');
        $this->view->setTemplateDir('./view/'.strtolower(substr(get_class($this), 0, -10)));
        $this->init();
    }

    public function init() { }
    public function preDispatch() { }
    public function postDispatch() { }

    public function flush() {

        if (!$this->context) {
            if(Request::getInstance()->isAjax())
                $this->context = 'json';
            else
                $this->context = 'html';
        }

        if($this->context == 'html') {
            header('Content-type: text/html');
            $output = $this->view->render();

            if($this->layout && !Request::getInstance()->isAjax()) {
                $layout = new View;
                $layout->setTemplateFile($this->layout);
                $layout->setTemplateDir('layout/');
                $layout->content = $output;
                $output = $layout->render();
            }
            echo $output;
        }
        else {
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json');
            echo json_encode($this->result);
        }
    }

    public function forward($controller, $action) {
        $fronController = FrontController::getInstance();
        $fronController->Routing->controller = $controller;
        $fronController->Routing->action = $action;
        $fronController->dispatch();
    }

    public function redirect($controller, $action) {
        $fronController = FrontController::getInstance();
        $fronController->Routing->controller = $controller;
        $fronController->Routing->action = $action;
        header('Location: '.FrontController::getInstance()->Routing->toString());
        exit;
    }

    public function getParam($name, $default = null) {
        return Request::getInstance()->getParam($name, $default);
    }

    //json, html ...
    public function setContext($type) {
        $this->context = $type;
    }

}
