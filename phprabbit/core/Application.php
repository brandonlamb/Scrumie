<?php
/**
 * Base Application Exception class
 */
class ApplicationException Extends Exception {}

require_once 'Asserts.php';

/**
 * Base application bootstrap class 
 */
class Application {
    /**
     * Returns instance of Application class
     * 
     * @param Config $configFile 
     */
    public function __construct($config = null) {
        //decide what config type we are dealing with
        if(is_file($config)) {
            require_once('ConfigIni.php');
            $config = new ConfigIni($config);
        } else if(is_array($config)) {
            $config = new ConfigArray($config);
        } else if (is_null($config)) {
            //skip config
        } else if ($config instanceof Config) {
            //skip config
        } else {
            throw new ApplicationException('Invalid config type');
        }

        if($config)
            $this->configure($config);
    }

    /**
     * Returns FrontController class
     * 
     * @return FrontController
     */
    public function getFrontController() {
        require_once 'FrontController.php';
        return FrontController::getInstance();
    }


    /**
     * Runs proper setup function for each config section
     * 
     * @param Config $config 
     * @return void
     */
    protected function configure(Config $config) {
        if($config->database) {
            $this->initDAO($config->database);
        }
    }

    /**
     * Initialize database adapter
     */
    protected function initDAO(stdClass $db) { 
        require_once 'DAO.php';
        if($db->driver == 'postgresql')
            $dsn = sprintf('pgsql:dbname=%s;host=%s;user=%s;port=%s;password=%s', $db->name, $db->host, $db->user, $db->port, $db->password);
        else 
            throw new ApplicationException('Invalid database driver');

        DAO::setAdapter(new DatabaseAdapter(new PDO($dsn)));
    }

}

