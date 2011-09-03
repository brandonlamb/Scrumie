<?php

class Relation {
    public $relationName;
    public $keyConstraints;
    public $oneToOne;

    public function __construct($relationName, array $keyConstraints, $oneToOne = false) {
        $this->relationName = $relationName;
        $this->keyConstraints = $keyConstraints;
        $this->oneToOne = $oneToOne;
    }
}
