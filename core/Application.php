<?php

require_once 'FrontController.php';
require_once 'View.php';
require_once 'Controller.php';

class Application {
    static protected $_instance;

    protected $FrontController;

    protected function __construct() {
        $this->FrontController = new FrontController;
    }

    public function getInstance() {
        if(self::$_instance)
            return self::$_instance;

        self::$_instance = new self;

        return self::$_instance;
    }

    public function getControllerName() {
        return $this->FrontController->getControllerName();
    }

    public function getActionName() {
        return $this->FrontController->getActionName();
    }

    public function dispatch($controller, $action) {
        return $this->FrontController->dispatch($controller, $action);
    }

    public function getRequest() {
        return $this->FrontController->Request;
    }

    public function isAjaxRequest() {
        return $this->FrontController->Request->isAjax();
    }
}

//debug function
function mpr($val, $die=false) {
    if(!headers_sent()) 
        header("content-type: text/plain");

    if (is_array($val) || is_object($val)) {
        print_r($val);

    if(is_array($val))
        reset($val);
    }   
    else
        var_dump($val);

    if($die)
    {   
        $trace = debug_backtrace();
        echo "--\n";
        echo sprintf('Who called me: %s line %s', $trace[0]['file'], $trace[0]['line']);
        die();
    }   
}
