<?php

class DataModelException extends Exception {
    const VARIABLE_NOT_FOUND = 1;
}

abstract class DataModel
{
    protected $data = array();

    public function __construct($data = array()) {
        foreach($data as $key => $value)
            $this->$key = $value;
    }

    public function __set($name, $value) {
        if(! array_key_exists($name, $this->data))
            throw new DataModelException(sprintf('Variable %s dosen\'t exists in %s', $name, get_class($this)), DataModelException::VARIABLE_NOT_FOUND);

        $magic_method = '__set_'.$name;
        if(method_exists($this, $magic_method))
            $this->$magic_method($value);
        else
            $this->data[$name] = $value;
    }

    public function __get($name) {
        if(! array_key_exists($name, $this->data))
            throw new DataModelException(sprintf('Variable %s dosen\'t exists in %s', $name, get_class($this)), DataModelException::VARIABLE_NOT_FOUND);
        return $this->data[$name];
    }

    public function getData() {
        return $this->data;
    }
}
