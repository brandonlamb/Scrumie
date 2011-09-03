<?php

class UserException extends Exception {};
class User extends DbModel
{
    const TABLE = 'user';
    const INDEX = 'id';

    protected $data = array(
        'id' => null,
        'login' => null,
        'password' => null,
        'email' => null,
    );

    protected $projects;

    public function _init() {
        $this->projects = new Relation('UserProject', array('id_user'=> 'id'));
    }

    public function assignProject($projectId) {
        $rel = new UserProject();
        $rel->id_user = $this->id;
        $rel->id_project = $projectId;
        return $rel->save();
    }

    static public function hasProjectWithId($userId, $projectId) {
        return DAO::get('UserProject')->exists(array('id_user'=>$userId, 'id_project'=>$projectId));
    }

    static public function registry($login, $password) {
        if(DAO::get('User')->exists(array('login'=>$login)))
            throw new UserException('Login already taken');

        $user = new User;
        $user->login = $login;
        $user->password = md5($password);
        $user->id = DAO::insert($user);
    }

    static public function authorize($login, $password) {
        return DAO::get('User')->exists(array('login'=>$login, 'password'=>md5($password)));
    }
}
