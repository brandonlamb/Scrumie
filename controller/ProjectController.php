<?php

class ProjectController extends AppController 
{
    public function addUserToProjectAction() {
        $user = User::getBy('login', $this->getParam('username'));
        if(!$user->count()) {
            throw new AppControllerException('Invalid username');
        }

        UserProject::assignProjectToUser($this->getCurrentProject(), current($user));
        $this->result = true;
    }

    public function addProjectAction() {
        DAO::query('begin');
        $user = $this->getCurrentUser();
        $project = new Project;
        $project->name = $this->getParam('name');
        $project->save();
        $user->assignProject($project->id);
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
            throw new AppControllerException(sprintf('You can delete sprint only from current selected projects'));

        $sprint->delete();
        $this->result = true;
    }

    public function renameSprintAction() {
        $sprint = Sprint::getById($this->getParam('id'));
        if($sprint->id_project != $this->getCurrentProjectId())
            throw new AppControllerException(sprintf('You can rename sprint only from current selected projects'));
        $sprint->name = $this->getParam('name');
        $sprint->save();
        $this->result = true;
    }

    public function saveTaskAction() {

        $taskId = (int) $this->getParam('taskId');
        if($taskId) {
            $task = Task::getById($taskId);
            if($task->id_project != $this->getCurrentProjectId())
                throw new AppControllerException(sprintf('Invalid project ID'));
        }
        else {
            $task = new Task;
            $task->id_project = $this->getCurrentProjectId();
        }

        $task->id_sprint = $this->getCurrentSprintId();
        $task->id_story = $this->getParam('storyId');
        $task->body = $this->getParam('body');
        $task->estimation = (int) $this->getParam('estimation');
        $task->done = (int) $this->getParam('done');
        $task->owner = $this->getParam('owner');
        $task->color = $this->getParam('color');
        $task->state = $this->getParam('state');
        $task->save();

        $this->result = $task->getId();
    }

    public function deleteTaskAction() {
        $task = Task::getById($this->getParam('id'));
        if($task->id_project != $this->getCurrentProjectId())
            throw new AppControllerException(sprintf('Invalid project ID'));
        $task->delete();
        $this->result = true;
    }

    public function deleteProjectAction() {
        $project = $this->getCurrentProject();
        $user = $this->getCurrentUser();
        UserProject::untouchProjectFromUser($project, $user);
        Project::deleteIfNoContributors($project);
        $this->result = true;
    }

    public function addNewUserStoryAction() {
        $story = new Story;
        $story->id_sprint = $this->getCurrentSprintId();
        $story->id_project = $this->getCurrentProjectId();
        $story->save();
        $this->result = $story->id;
    }

    public function deleteUserStoryAction() {
        $story = Story::getById((int) $this->getParam('id'));

        if($story->id_project != $this->getCurrentProjectId()) {
            throw new AppControllerException('Invalid project id for user story');
        }

        $story->delete();
        $this->result = true;
    }

    public function detachUserStoryAction() {
        $story = Story::getById((int) $this->getParam('id'));

        if($story->id_project != $this->getCurrentProjectId()) {
            throw new AppControllerException('Invalid project id for user story');
        } 

        $story->id_sprint = null;
        $story->save();
        $this->result = true;
    }

    public function attachUserStoryAction() {
        $story = Story::getById((int) $this->getParam('id'));

        if($story->id_project != $this->getCurrentProjectId()) {
            throw new AppControllerException('Invalid project id for user story');
        } 

        $story->id_sprint = $this->getCurrentSprintId();
        $story->save();
        $this->result = true;
    }

    public function renameUserStoryAction() {
        $story = Story::getById((int) $this->getParam('id'));
        if($story->id_project != $this->getCurrentProjectId()) {
            throw new AppControllerException('Invalid project id for user story');
        }
        $story->name = $this->getParam('name');
        $story->save();
        $this->result = true;
    }
}
