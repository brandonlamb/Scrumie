<?php
require_once('DataModel.php');

class User extends DataModel 
{
    protected $data = array(
        'id_user' => null,
        'login' => null,
        'password' => null,
    );

    const _CLASS_ = __CLASS__;
    const TABLE = 'user';
    const INDEX = 'id_user';

    static public function isRegistered($login) {
        $result = self::fetch(sprintf("SELECT id_user FROM user WHERE login = '%s' LIMIT 1", $login));
        return ($result) ? true : false;
    }

    static public function authorize($login, $password) {
        $result = self::fetch(sprintf("SELECT id_user FROM user WHERE login = '%s' and password = '%s' LIMIT 1", $login, md5($password)));
        return ($result) ? true : false;
    }
}
