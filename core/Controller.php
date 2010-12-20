<?php

abstract class Controller {
    protected $templateFile = 'index.phtml';
    protected $templateDir;
    protected $view;
    protected $layout;
    protected $result;

    final public function __construct() {
        $this->view = new View;
        $this->templateDir = './view/'.strtolower(substr(get_class($this), 0, -10));
        $this->init();
    }

    protected function init() { }

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

    protected function _forward($controller, $action) {
        Application::getInstance()->dispatch($controller, $action);
    }

    protected function _redirect($controller, $action) {
        header(sprintf('Location: ?controller=%s&action=%s', $controller, $action));
        exit;
    }

    protected function _getParam($name, $default = null) {
        return Application::getInstance()->getRequest()->getParam($name, $default);
    }
}
