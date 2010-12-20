<?php

class Request {
    protected $get;
    protected $post;
    protected $isAjax;

    public function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
    }

    public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER ['HTTP_X_REQUESTED_WITH']  == 'XMLHttpRequest';
    }

    public function isCli() {
        return isset($_SERVER['SHELL']) ? true : false;
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

}
