<?php

require_once('./models/User.php');

class UserService extends Service
{
    public function registryUser($login, $password) {
        if(User::isRegistered($login))
            throw new Exception('User already registered');

        $user = new User();
        $user->login = $login;
        $user->password = md5($password);

        $user->id_user = $user->insert();
    }

    public function authorize($login, $password) {
        return User::authorize($login, $password);
    }
}
