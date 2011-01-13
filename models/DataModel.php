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
            $values[] = '"'.$column.'"' . " = '$value'";

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

    static public function fetchBy($column, $value, array $order = array(), $limit = null) {
        return self::fetch(sprintf("SELECT * FROM %s WHERE %s = '%s'", static::TABLE, $column, $value), $order, $limit);
    }

    static public function fetchByColumns(array $columns, array $order = array(), $limit = null) {

        $where = array();

        foreach($columns as $key => $values)
            $where[] = "$key = '$values'";

        $sql = sprintf("SELECT * FROM %s WHERE %s", static::TABLE, join(' AND ', $where));
        return self::fetch(sprintf("SELECT * FROM %s WHERE %s", static::TABLE, join(' AND ', $where)), $order, $limit);
    }

    static public function fetchAll() {
        return self::fetch(sprintf('SELECT * FROM %s', static::TABLE));
    }

    static public function fetchOne($sql, $column = null) {
        $result = self::fetch($sql);

        if(!$result)
            return false;

        $class = static::_CLASS_;
        return ($column) ? $result[0]->$column : new $class($result[0]);
    }

    static public function fetch($sql, array $order = array(), $limit = null) {
        $order = ($order) ? ' ORDER BY '.join(',', $order) : '';
        $limit = ($limit) ? " LIMIT $limit" : '';
        $collection = array();

        if($result = self::query($sql.$order.$limit)) {
            $data = $result->fetchAll(PDO::FETCH_CLASS, 'stdClass');

            $class = static::_CLASS_;
            foreach($data as $object)
                $collection[] = (static::_CLASS_ == __CLASS__) ? $object : new $class($object);
        }

        return $collection;
    }

    static public function query($sql) {
        if(! self::$_db)
            self::_initDatabase();
        return self::$_db->query($sql);
    }

    static private function _initDatabase() {
        self::$_db = new Database();
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
