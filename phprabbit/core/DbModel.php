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

    public function setId($value) {
        $this->data[constant(get_class($this).'::INDEX')] = $value;
    }

    static public function getModelName() {
        return get_called_class();
    }

    public function save() {
        if($this->getId() === null)
            $this->setId(DAO::insert($this));
        else 
            return DAO::update($this);
    }

    public function delete() {
        return DAO::delete(get_class($this), $this->getId());
    }

    public function __get($name) {
        $val = parent::__get($name);

        if($val instanceof Relation) {
            foreach($val->keyConstraints as $fk => $pkey) {
                $val->keyConstraints[$fk] = $this->{$pkey};
            }

            $data = DAO::get($val->relationName)->by($val->keyConstraints);
            if($val->oneToOne) {
                $data = current($data);
            }
            $this->$name = $data;
            $val = $data;
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

    static public function getBy($column, $value = null) {
        return DAO::get(self::getModelName())->by($column,$value);
    }

    static public function getById($id) {
        if(! $model = DAO::get(self::getModelName())->byId($id))
            return false;

        return $model;
    }
}
