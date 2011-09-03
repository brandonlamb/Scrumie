<?php
class Project extends DbModel
{
    protected $data = array(
        'id' => null,
        'name' => null,
        'password' => null, //depreciated
    );

    protected $users;
    protected $sprints;
    protected $tasks;

    const TABLE = 'project';
    const INDEX = 'id';

    public function _init() {
        $this->sprints = new Relation('Sprint', array('id_project'=>'id'));
        $this->tasks = new Relation('Task', array('id_project'=>'id'));
    }
}
