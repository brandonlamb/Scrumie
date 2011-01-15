<?php

class Sprint extends DbModel
{
    protected $data = array(
        'id' => null,
        'name' => null,
        'startdate' => null,
        'id_project' => null,
    );

    const TABLE = 'sprint';
    const INDEX = 'id';
}
