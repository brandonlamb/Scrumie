<?php
class DbResult {
    public $statement;
    protected $fetchMode;
        
    public function __construct(PdoStatement $statement) {
        $this->statement = $statement;
        $this->fetchMode = PDO::FETCH_OBJ;
        $statement->setFetchMode($this->fetchMode);
    }

    public function __call($name, $arguments) {
        return call_user_func_array(array($this->statement, $name), $arguments);
    }

    public function pop() {
        return $this->statement->fetchObject();
    }
}
