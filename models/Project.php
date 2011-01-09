<?php
require_once('DataModel.php');

class User extends DataModel 
{
    protected $data = array(
        'id' => null,
        'name' => null,
        'password' => null,
    );

    const _CLASS_ = __CLASS__;
    const TABLE = 'project';
    const INDEX = 'id';

    static public function isRegistered($name) {
        $result = self::fetch(sprintf("SELECT id FROM project WHERE name = '%s' LIMIT 1", $name));
        return ($result) ? true : false;
    }

    static public function authorize($name, $password) {
        $result = self::fetch(sprintf("SELECT id FROM project WHERE name = '%s' and password = '%s' LIMIT 1", $name, md5($password)));
        return ($result) ? true : false;
    }
}
