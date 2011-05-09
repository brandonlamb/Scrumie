<?php

class Relation {
    public $name;
    public $index;

    public function __construct($modelName, $index) {
        $this->name = $modelName;
        $this->index = $index;
    }
}
