<?php

require_once('DataModel.php');

abstract class DbModel extends DataModel
{
    const INDEX = null;
    const TABLE = null;

    public function getId() {
        return $this->data[self::INDEX];
    }
}
