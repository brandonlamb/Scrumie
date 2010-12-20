<?php

require_once('./core/ActiveRecord.php');

abstract class DataModel implements ActiveRecordInterface
{
    static protected $_db;
    const _CLASS_ = __CLASS__;
    const INDEX = null;
    const TABLE = null;

    protected $data = array();

    public function __construct($data = array()) {
        foreach($data as $key => $value)
            $this->$key = $value;
    }

    public function insert() {
        $data = $this->data;
        unset($data[static::INDEX]);

        $columns = array();
        $values = array();
        foreach($data as $column => $value) {
            $columns[] = $column;
            $values[] = "'$value'";
        }
        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", static::TABLE, join(',',$columns), join(',', $values));

        $result = self::query($sql);
        return self::$_db->lastInsertId();
    }

    public function update() {
        $data = $this->data;
        unset($data[static::INDEX]);
        $values = array();

        foreach($data as $column => $value)
            $values[] = $column ."='$value'";

        $sql = sprintf("UPDATE %s SET %s WHERE %s = %s", static::TABLE, join(',',$values), static::INDEX, $this->getId());
        $result = self::query($sql);
        return true;
    }

    public function delete() {
        $sql = sprintf("DELETE FROM %s WHERE %s = '%s'", static::TABLE, static::INDEX, $this->getId());
        self::query($sql);
    }

    static public function getById($id) {
        if(! $result = self::fetch(sprintf('SELECT * FROM %s WHERE %s = %s', static::TABLE, static::INDEX, $id)))
            throw new InvalidArgumentException(sprintf('%s with id: %s dosent exists',static::_CLASS_, $id));

        $class = static::_CLASS_;
        return new $class($result[0]);
    }

    static public function fetchBy($column, $value) {
        return self::fetch(sprintf("SELECT * FROM %s WHERE %s = '%s'", static::TABLE, $column, $value));
    }

    static public function fetchAll() {
        return self::fetch(sprintf('SELECT * FROM %s', static::TABLE));
    }

    static protected function fetch($sql) {
        $result = self::query($sql);

        $data = array();
        while($res = $result->fetchArray(SQLITE3_ASSOC)) {
            $data[] = (object) $res;
        }

        return $data;
    }

    static protected function query($sql) {
        if(! self::$_db)
            self::_initDatabase();

        return self::$_db->query($sql);
    }

    static private function _initDatabase() {
        require_once('./lib/Database.php');
        $dbfile = realpath('./data/scrumie.sqlite');

        if(! is_file($dbfile))
            throw new RuntimeException('Missing database file');

        if(! is_readable($dbfile))
            throw new RuntimeException('Database file is not readeable');

        self::$_db = new Database($dbfile);
    }

    public function __set($name, $value) {
        if(! array_key_exists($name, $this->data))
            throw new InvalidArgumentException(sprintf('Variable %s dosen\'t exists in %s', $name, static::_CLASS_));

        $magic_method = '__set_'.$name;
        if(method_exists($this, $magic_method))
            $this->$magic_method($value);
        else
            $this->data[$name] = $value;
    }

    public function __get($name) {
        if(! array_key_exists($name, $this->data))
            throw new InvalidArgumentException(sprintf('Variable %s dosen\'t exists in %s', $name, static::_CLASS_));
        return $this->data[$name];
    }

    public function getId() {
        return $this->data[static::INDEX];
    }

}
