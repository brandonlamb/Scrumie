<?php

class Database
{
    private $db;

    public function __construct($dbname) {
        $this->db = new SQLite3($dbname);
    }

    public function query($sql) {
        return $this->db->query($sql);
    }

    public function lastInsertId() {
        return $this->db->lastInsertRowID();
    }
}
