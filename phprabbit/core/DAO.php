<?php
require_once('DatabaseAdapter.php');
require_once('Collection.php');
require_once('DbModel.php');

class DAOException extends Exception {
    const NO_RECORD = 1;
}

class DAO {

    public $modelName;
    public $tableName;
    public $indexName;
    public $sequenceName;
    static public $dbAdapter;

    public function __construct($className) {
        $this->modelName = $className;
        $this->collectionName = $className.'Collection';
        $this->tableName = constant($className.'::TABLE');
        $this->indexName = constant($className.'::INDEX');
        $this->sequenceName = $this->tableName . '_' . $this->indexName . '_seq';
    }

    static public function setAdapter(DatabaseAdapter $adapter) {
        self::$dbAdapter = $adapter;
    }

    static public function get($className) {
        $adapter = new self($className);
        return $adapter;
    }

    public function byId($value) {
        if(! $result = $this->by($this->indexName, $value))
            throw new DAOException(sprintf('%s with id %s dosen\'t exists', $this->modelName, $value));

        return $result[0];
    }

    public function by($columnName, $value = null, $order = null, $limit = null) {

        if(is_array($columnName)) { //becouse of php dosent have method overload we need to do this like that :(
            $order = is_array($value) ? $value : array();
            $limit = $order;
            return $this->fetchBy($columnName, $order, $limit);
        }

        if($value === null)
            //but what to do when we want get all with null values?
            throw new InvalidArgumentException('Second argument can\'t be null');

        if($order === null) //correct order argument
            $order = array();

        if(is_array($value))
            $where = sprintf(" IN (%s)", $this->serializeForIn($value));
        else
            $where = sprintf(" = '%s'", $value);

        $sql = sprintf('SELECT * FROM "%s" WHERE %s %s', $this->tableName, $columnName, $where);
        return $result = $this->fetch($sql, $order, $limit);
    }

    public function exists(array $columns) {
        $collection = $this->fetchBy($columns, array());
        return ($collection->count()) ? true : false;
    }

    public function count(array $columns) {

        $where = array();
        foreach($columns as $key => $value)
            $where[] = sprintf('"%s" %s', $key, self::prepareValueForSql($value));

        $sql = sprintf('SELECT count(*) as cnt FROM "%s" WHERE %s', $this->tableName, join(' AND ', $where));

        return self::query($sql)->pop()->cnt;
    }

    public function fetchAll() {
        return self::fetch(sprintf('SELECT * FROM "%s"', $this->tableName));
    }

    public function fetchBy(array $columns, array $order = array(), $limit = null) {

        $where = array();
        foreach($columns as $key => $value)
            $where[] = sprintf('"%s" %s', $key, self::prepareValueForSql($value));

        $sql = sprintf('SELECT * FROM "%s" WHERE %s', $this->tableName, join(' AND ', $where));

        return $this->fetch($sql, $order, $limit);
    }

    public function fetch($sql, array $order = array(), $limit = null) {
        $order = ($order) ? ' ORDER BY '.join(',', $order) : '';
        $limit = ($limit) ? " LIMIT $limit" : '';
        $collection = new $this->collectionName;

        if($result = self::query($sql.$order.$limit)) {
            if($data = $result->fetchAll()) {
                foreach($data as $object)
                    $collection[] = new $this->modelName($object);
            }
        }

        return $collection;
    }

    static public function query($sql) {
        $data = self::$dbAdapter->query($sql);
        return $data;
    }

    static public function insert(DbModel $model) {
        $data = $model->getData();
        $adapter = new self(get_class($model));
        unset($data[$adapter->indexName]);

        $columns = array();
        $values = array();
        foreach($data as $column => $value) {
            $columns[] = '"'.$column.'"';
            $values[] = self::prepareValueForSql($value, false);
        }

        $sql = sprintf('INSERT INTO "%s" (%s) VALUES (%s)', $adapter->tableName, join(',',$columns), join(',', $values));

        $result = self::query($sql);
        return self::$dbAdapter->lastInsertId($adapter->sequenceName);
    }

    static public function update(DbModel $model) {
        $data = $model->getData();
        $adapter = new self(get_class($model));
        unset($data[$adapter->indexName]);

        $values = array();
        foreach($data as $column => $value)
            $values[] = sprintf('"%s" = %s', $column, self::prepareValueForSql($value, false));

        $sql = sprintf('UPDATE "%s" SET %s WHERE %s = %s', $adapter->tableName, join(',',$values), $adapter->indexName, $model->getId());

        $result = self::query($sql);
        return true;
    }

    public function serializeForIn(array $values) {
        $result = array();
        foreach($values as $value) {
            $result[] = "'$value'";
        }

        return join(',', $result);
    }

    static public function delete($modelName, $id) {
        $adapter = new self($modelName);
        $sql = sprintf("DELETE FROM \"%s\" WHERE %s = '%s'", $adapter->tableName, $adapter->indexName, $id);
        self::query($sql);
    }

    static public function prepareValueForSql($value, $withOperator = true) {
        $operator = '=';
        if(is_string($value))
            $result = $value ? "'$value'" : 'NULL';
        elseif(is_numeric($value))
            $result = $value === null ? 'NULL' : $value;
        elseif($value === null) {
            $operator = 'IS';
            $result = 'NULL';
        }
        elseif(is_object($value)) {
            $result = "'".((string) $value)."'";
        }
        elseif(is_bool($value)) {
            $result = ($value) ? 'True' : 'False';
        }
        else 
            $result = $value;

        return ($withOperator) ? "$operator $result" : $result;
    }
}
