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
        $this->users = new Relation('UserProject', array('id_project'=>'id'));
    }

    static public function deleteIfNoContributors(Project $project) {
        $collection = UserProject::getBy('id_project', $project->id);
        if(count($collection) == 0) {
            $project->delete();
        }
    }

    public function getDetachedStories() {
        return Story::getBy(array('id_project'=>$this->id, 'id_sprint'=>null));
    }
}
