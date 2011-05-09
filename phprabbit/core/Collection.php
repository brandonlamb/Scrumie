<?php

abstract class Collection implements ArrayAccess, Iterator {
    protected $data = array();
    protected $position = 0;

    public function offsetSet($offset, $value) {
        if(is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->data[$offset]) ? $this->data[$offset] : null; //xxx throw exception or return null?
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->data[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->data[$this->position]);
    }

    public function __set($name, $value) {
        $this->data[$this->position]->$name = $value;
    }

    public function __get($name) {
        return $this->data[$this->position]->$name;
    }

    public function update() {
        foreach($this->data as $model) {
            DAO::update($model);
        }
    }

    public function insert() {
        foreach($this->data as $model) {
            DAO::insert($model);
        }
    }
}
