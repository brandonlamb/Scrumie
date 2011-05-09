<?php
require_once(PHP_RABBIT_PATH.'core/xIni.php');
class LiquibaseUpdate extends ClixTask
{
    const HINT = 'Update database by running liquibase';

    public $params_config = '{ 
        "config": {
            "description": "Config file location",
            "mandatory": true
        }
    }';

    public function execute() 
    {
        $config = new xIni($this->config); 

        $db = $config->database;

        if($db->driver == 'postgresql') {
            $driver = 'org.postgresql.Driver';
            $jdbc = 'postgresql';
            $classpath = PHP_RABBIT_PATH.'liquibase/lib/postgresql.jdbc.jar';
        }

        $config_file_path = pathinfo($this->config, PATHINFO_DIRNAME);
        $changelogPath = realpath($config_file_path . '/' . $config->liquibase->changeLogFile);
        
        $settings = sprintf('--classpath=%s --driver=%s --logLevel=info --url="jdbc:%s://%s:%s/%s" --username=%s --password=%s --changeLogFile="%s" --contexts=%s', 
                $classpath,
                $driver, 
                $jdbc, 
                $db->host, 
                $db->port, 
                $db->name, 
                $db->user, 
                $db->password, 
                $changelogPath,
                $config->liquibase->context);

        $command = 'update';
        $cmd = sprintf('cd %sliquibase && java -jar liquibase.jar %s %s', PHP_RABBIT_PATH, $settings, $command);
        echo $cmd;
        passthru($cmd);
    }
}
