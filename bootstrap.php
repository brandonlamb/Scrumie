<?php

define('APP_PATH', pathinfo(__FILE__, PATHINFO_DIRNAME) . '/');   //root path of application
define('PHP_RABBIT_PATH', APP_PATH . '../../');            //path to phprabbit framework
set_error_handler('ErrorHandler');                          //All php errors like warning, notice etc will be transformed to FataErrorException

if(! is_readable(PHP_RABBIT_PATH)) {
    die ( sprintf("Setup error: Path %s dosen't exist or it's not readable", PHP_RABBIT_PATH) );
}

require_once (PHP_RABBIT_PATH.'core/Application.php');

//simple autoloader all application classes are added to autoload.ini file
function __autoload($name) {
    static $data;
    if(!$data)
        $data = parse_ini_file('autoload.ini');
    if(array_key_exists($name, $data)) {
        require_once(APP_PATH.DIRECTORY_SEPARATOR.$data[$name]);
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

//default error handler
class FatalErrorException extends Exception {};
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new FatalErrorException(sprintf('%s in file %s line %s', $errstr, $errfile, $errline));
}
