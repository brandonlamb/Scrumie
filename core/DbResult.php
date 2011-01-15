<?php
class DbResult {
    public $statement;
        
    public function __construct(PdoStatement $statement) {
        $this->statement = $statement;
    }

    public function __call($name, $arguments) {
        return call_user_func_array(array($this->statement, $name), $arguments);
    }

    public function pop() {
        return $this->statement->fetchObject();
    }
}
