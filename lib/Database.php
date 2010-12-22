<?php

class Database
{
    private $db;

    public function __construct() {
        $this->db = new PDO('sqlite:'.DATABASE);
    }

    public function query($sql) {
        return $this->db->query($sql);
    }

    public function lastInsertId() {
        return $this->db->lastInsertId();
    }

    static public function checkDatabaseConnection() {
        if(! class_exists('PDO'))
            throw new RuntimeException('Missing PDO class');

        if(! is_file(DATABASE))
            throw new RuntimeException(sprintf('Missing %s database file', DATABASE));

        if(! is_readable(DATABASE))
            throw new RuntimeException(sprintf('Database %s file is not readable', DATABASE));

        if(! is_writable(DATABASE))
            throw new RuntimeException(sprintf('Database %s file is not writeable', DATABASE));
    }
}
