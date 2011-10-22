<?php

class Request {
    static protected $instance;

    protected $get;
    protected $post;
    protected $isAjax;

    protected function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
    }

    static public function getInstance() {
        if(!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER ['HTTP_X_REQUESTED_WITH']  == 'XMLHttpRequest';
    }

    public function isCli() {
        return isset($_SERVER['SHELL']) ? true : false;
    }

    public function getRequestUri() {
        return $_SERVER['REQUEST_URI'];
    }

    public function isHttpRequest() {
        return ($this->isAjax() || $this->isCli()) ? false : true;
    }

    public function getParam($name, $default=null) {
        if(array_key_exists($name, $this->get))
            return $this->get[$name];

        if(array_key_exists($name, $this->post))
            return $this->post[$name];

        return $default;
    }

    public function setParams(array $params) {
        $this->get = $params;
    }
}
