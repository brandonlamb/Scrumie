<?php

require_once('./models/Project.php');

class ProjectService extends Service
{
    public function registry($name, $password) {
        
        if(DAO::get('Project')->exists(array('name'=>$name)))
            throw new Exception('Project already registered');

        $project = new Project();
        $project->name = $name;
        $project->password = md5($password);
        $project->id = DAO::insert($project);
    }

    /**
     * When autorization fail return false otherwise returns project id
     */
    public function authorize($name, $password) {
        $project = DAO::get('Project')->fetchBy(array('name'=>$name, 'password' => md5($password)));
        return $project[0]->id;
    }
}
