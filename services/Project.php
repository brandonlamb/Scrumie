<?php

require_once('./models/Project.php');

class ProjectService extends Service
{
    public function registry($name, $password) {
        if(Project::isRegistered($name))
            throw new Exception('Project already registered');

        $project = new Project();
        $project->name = $name;
        $project->password = md5($password);

        $project->id = $project->insert();
    }

    public function authorize($name, $password) {
        return Project::authorize($name, $password);
    }
}
