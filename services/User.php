<?php

require_once('./models/User.php');

class UserService extends Service
{
    public function registryUser($email, $password) {
        if(User::isRegistered($email))
            throw new Exception('User already registered');

        $user = new User();
        $user->email = $email;
        $user->password = md5($password);

        $user->id_user = $user->insert();
    }

    public function authorize($email, $password) {
        return User::authorize($email, $password);
    }
}
