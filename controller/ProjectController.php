<?php

class ProjectController extends AppController 
{
    public function importAction() {
        $project = Project::getBy(array( 'name' => $this->getParam('project'), 'password' => md5($this->getParam('password'))));
        if(!$project->count()) {
            throw new AppControllerException('Invalid project name or password');
        } 
        UserProject::assignProjectToUser(current($project), $this->getCurrentUser());
        $this->result = true;

    }

    public function addProjectAction() {
        DAO::query('begin');
        $user = $this->getCurrentUser();
        $project = new Project;
        $project->name = $this->getParam('name');
        $project_id = $project->save();
        $user->assignProject($project_id);
        DAO::query('commit');
        $this->result = true;
    }

    public function addNewSprintAction() {
        $sprint = new Sprint();
        $sprint->name = $this->getParam('name');
        $sprint->startdate = date('Y-m-d H:i:s', time());
        $sprint->id_project = $this->getCurrentProjectId();
        $sprint->save();
        $this->result = true;
    }

    public function deleteSprintAction() {
        $sprint = Sprint::getById($this->getParam('id'));

        if($sprint->id_project != $this->getCurrentProjectId())
            throw new BoardControllerException(sprintf('You can delete sprint only from current selected projects'));

        $sprint->delete();
        $this->result = true;
    }

    public function renameSprintAction() {
        $sprint = Sprint::getById($this->getParam('id'));
        if($sprint->id_project != $this->getCurrentProjectId())
            throw new BoardControllerException(sprintf('You can rename sprint only from current selected projects'));
        $sprint->name = $this->getParam('name');
        $sprint->save();
        $this->result = true;
    }

    public function saveTaskAction() {

        $taskId = (int) $this->getParam('taskId');
        if($taskId) {
            $task = Task::getById($taskId);
            if($task->id_project != $this->getCurrentProjectId())
                throw new BoardControllerException(sprintf('Invalid project ID'));
        }
        else {
            $task = new Task;
            $task->id_project = $this->getCurrentProjectId();
        }

        $task->id_sprint = $this->getCurrentSprintId();
        $task->body = $this->getParam('body');
        $task->estimation = (int) $this->getParam('estimation');
        $task->done = (int) $this->getParam('done');
        $task->owner = $this->getParam('owner');
        $task->state = $this->getParam('state');
        $task->save();

        $this->result = $task->getId();
    }

    public function deleteTaskAction() {
        $task = Task::getById($this->getParam('taskId'));
        if($task->id_project != $this->getCurrentProjectId())
            throw new BoardControllerException(sprintf('Invalid project ID'));
        $task->delete();
        $this->result = true;
    }

    public function reorderTaskAction() {
        $order = $this->getParam('order');
        foreach($order as $index => $task_id) {
            $task = Task::getById($task_id);
            if($task->id_project != $this->getCurrentProjectId())
                throw new BoardControllerException(sprintf('Invalid project ID'));
            $task->order = $index;
            $task->save();
        }
    }


}
