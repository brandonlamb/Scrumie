<?php

class Request {
    protected $get;
    protected $post;

    public function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
    }

    public function getParam($name, $default=null) {
        if(array_key_exists($name, $this->get))
            return $this->get[$name];

        if(array_key_exists($name, $this->post))
            return $this->post[$name];

        return $default;
    }

}
