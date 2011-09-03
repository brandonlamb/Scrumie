<?php

class UserProjectException extends Exception {}
class UserProject extends DbModel
{
    const TABLE = 'user_project';
    const INDEX = 'id';

    protected $data = array(
        'id' => null,
        'id_user' => null,
        'id_project' => null,
    );

    protected $project;

    public function _init() {
        $this->project = new Relation('Project', array('id' => 'id_project'), true);
    }

    static public function assignProjectToUser(Project $project, User $user) {
        try {
        $model = new self;
        $model->id_user = $user->id;
        $model->id_project = $project->id;
        $model->save();
        return $model;
        } catch (PDOException $e) {
            if($e->getCode() == 23505) {
                throw new UserProjectException('Project already assigned to user');
            } else {
                throw $e;
            }
        }
    }
}
