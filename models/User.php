<?php
require_once('DataModel.php');

class User extends DataModel 
{
    protected $data = array(
        'id_user' => null,
        'email' => null,
        'password' => null,
    );

    const _CLASS_ = __CLASS__;
    const TABLE = 'user';
    const INDEX = 'id_user';

    static public function isRegistered($email) {
        $result = self::fetch(sprintf("SELECT id_user FROM user WHERE email = '%s' LIMIT 1", $email));
        return ($result) ? true : false;
    }

    static public function authorize($email, $password) {
        $result = self::fetch(sprintf("SELECT id_user FROM user WHERE email = '%s' and password = '%s' LIMIT 1", $email, md5($password)));
        return ($result) ? true : false;
    }
}
