<?php

class Story extends DbModel
{
    const TABLE = 'story';
    const INDEX = 'id';

    protected $data = array(
        'id' => null,
        'id_sprint' => null,
        'id_project' => null,
        'name' => 'As an user I... (Double click to edit)',
    );

    public function getTasks() {
        if($this->id) {
            return Task::getBy('id_story', $this->id);
        } else {
            return new TaskCollection;
        }
    }

    public function getSprint() {
        return Sprint::getById($this->id_sprint);
    }

    public function save() {
        if(!$this->id_sprint) {
            foreach($this->getTasks() as $task) {
                $task->state = 'todo';
                $task->save();
            }
        }
        parent::save();
    }
}
