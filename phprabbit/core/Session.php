<?php

class SessionException extends Exception {}
class Session {
    public $data = array();
    protected function __construct() {
        if(isset($_SESSION)) {
            $this->data = &$_SESSION;
        } 
    }

    static public function getInstance() {
        static $instance = null;
        if (!$instance)
            $instance = new self;

        return $instance;
    }

    public function _set($name, $value) {
        $this->data[$name] = $value;
    }

    public function _get($name) {
        if(!array_key_exists($name, $this->data))
            return null;

        return $this->data[$name];
    }

    static public function destroy() {
        session_destroy();
    }

    static public function set($name, $value) {
        return self::getInstance()->_set($name, $value);
    }

    static public function get($name) {
        return self::getInstance()->_get($name);
    }

}

