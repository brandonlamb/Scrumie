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

        if(! is_writable('data'))
            throw new RuntimeException(sprintf('Directory ./data is not writable'));

        if(! is_file(DATABASE)) { 
            //when database dosen't exist this will crate new from migration file
            $newDb = new PDO('sqlite:'.DATABASE); 
            $sql = file_get_contents('data/scrumie.sql');
            foreach(explode("\n", $sql) as $query) {
                if(!$query)
                    continue;

                $result = $newDb->query($query);
            }
        }

        if(! is_readable(DATABASE))
            throw new RuntimeException(sprintf('Database %s file is not readable', DATABASE));

        if(! is_writable(DATABASE))
            throw new RuntimeException(sprintf('Database %s file is not writeable', DATABASE));
    }
}
