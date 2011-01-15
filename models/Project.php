<?php
class Project extends DbModel
{
    protected $data = array(
        'id' => null,
        'name' => null,
        'password' => null,
    );

    const TABLE = 'project';
    const INDEX = 'id';
}
