<?php

abstract class Controller {
    public $templateFile = 'index.phtml';
    public $templateDir;
    public $view;
    public $layout;
    public $result;
    public $calledMethod;

    final public function __construct() {
        $this->view = new View;
        $this->templateDir = './view/'.strtolower(substr(get_class($this), 0, -10));
        $this->init();
    }

    public function init() { }
    public function preDispatch() { }
    public function postDispatch() { }

    public function flush() {
        if(Application::getInstance()->isAjaxRequest()) {
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json');
            echo json_encode($this->result);
        }
        else {
            header('Content-type: text/html');
            $this->view->setTemplateFile($this->templateFile);
            $this->view->setTemplateDir($this->templateDir);
            $output = (string) $this->view;

            if($this->layout) {
                $layout = new View;
                $layout->setTemplateFile($this->layout);
                $layout->setTemplateDir('./layout/');
                $layout->content = $output;

                $output = (string) $layout;
                echo $output;
            }
        }

    }

    public function _forward($controller, $action) {
        Application::getInstance()->dispatch($controller, $action);
    }

    public function _redirect($controller, $action) {
        header(sprintf('Location: ?controller=%s&action=%s', $controller, $action));
        exit;
    }

    public function _getParam($name, $default = null) {
        return Application::getInstance()->getRequest()->getParam($name, $default);
    }
}
