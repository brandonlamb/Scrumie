<?php

require_once('DataModel.php');
require_once('Relation.php');

abstract class DbModel extends DataModel
{
    const INDEX = null;
    const TABLE = null;

    public function getId() {
        return $this->data[constant(get_class($this).'::INDEX')];
    }

    public function __get($name) {
        $val = parent::__get($name);

        if($val instanceof Relation) {
            $val = DAO::get($val->name)->byId($this->{$val->index});
            $this->$name = $val;
        }
        
        return $val;
    }

    public function getRelationObject($objectName, $indexKey, $destinationVariableName = null) {
        if(!isset($this->$destinationVariableName))
            $result = DAO::get($objectName)->byId($indexKey);

        if($destinationVariableName)
            $this->$destinationVariableName = $result;
        else {
            $className = get_class($result);
            $this->$className = $result;
        }
        return $result;
    }
}
