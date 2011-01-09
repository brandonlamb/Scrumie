<?php

require_once('./models/Project.php');

class ProjectService extends Service
{
    public function registryUser($name, $password) {
        if(Project::isRegistered($name))
            throw new Exception('Project already registered');

        $project = new Project();
        $project->name = $project;
        $project->password = md5($password);

        $project->id = $porject->insert();
    }

    public function authorize($name, $password) {
        return Project::authorize($login, $password);
    }
}
