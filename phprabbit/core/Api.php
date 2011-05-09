<?php

abstract class ApiException extends Exception {}
abstract class Api
{
    static private $_api = array();

    static public function getApi($name) {
        if(array_key_exists($name, self::$_api))
            return self::$_api[$name];

        require_once ('./api/'.$name.'Api.php');

        $class = $name.'Api';

        self::$_api[$name] = $api = new $class;

        return $api;
    }
}
