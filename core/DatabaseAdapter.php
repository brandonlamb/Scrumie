<?php

require_once ('DbResult.php');

class DatabaseAdapter
{
    public $db;

    public function __construct(PDO $pdo_adapter) {
        $this->db = $pdo_adapter;
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function __call($name, $arguments) {
        return call_user_func_array(array($this->db, $name), $arguments);
    }

    public function query($sql) {
        $statement = $this->db->query($sql);
        return new DbResult($statement);
    }
}

