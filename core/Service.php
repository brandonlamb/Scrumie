<?php

abstract class Service 
{
    static private $_services = array();

    public function getService($name) {
        if(array_key_exists($name, self::$_services))
            return self::$_services[$name];

        require_once ('./core/Service.php');
        require_once ('./services/'.$name.'.php');

        $class = $name.'Service';

        self::$_services[$name] = $service = new $class;

        return $service;
    }
}
