<?php

require_once 'FrontController.php';
require_once 'View.php';
require_once 'Controller.php';
require_once 'Asserts.php';

class ApplicationException Extends Exception {}
class Application {
    static protected $_instance;
    protected $routingClass = 'BaseRouting';

    static public function getInstance($configFile = null) {
        if(self::$_instance)
            return self::$_instance;

        self::$_instance = new self($configFile);

        return self::$_instance;
    }

    protected function __construct($configFile) {
        if($configFile)
            $this->configure($configFile);
    }


    public function configure($configFile) {
        require_once 'xIni.php';
        $config = new xIni($configFile);

        if(isset($config->database)) {
            $this->configureDAO($config->database);
        }
    }

    public function configureDAO(stdClass $db) { require_once 'DAO.php';
        if($db->driver == 'postgresql')
            $dsn = sprintf('pgsql:dbname=%s;host=%s;user=%s;port=%s;password=%s', $db->name, $db->host, $db->user, $db->port, $db->password);
        else 
            throw new ApplicationException('Invalid database driver');

        DAO::setAdapter(new DatabaseAdapter(new PDO($dsn)));
    }
}

