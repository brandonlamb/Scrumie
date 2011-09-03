<?php

class ProjectToUser extends DbModel
{
    const TABLE = 'user_project';
    const INDEX = 'id_project';

    protected $data = array(
        'id' => null,
        'id_user' => null,
        'id_project' => null,
    );
}
