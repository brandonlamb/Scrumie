<?php

require_once ('./models/Sprint.php');
class SprintService extends Service
{
    public function fetchAll() {
        return Sprint::fetchAll();
    }

    public function getById($id) {
        return Sprint::getById($id);
    }

    public function addNewSprint($sprintName) {
        $sprint = new Sprint();
        $sprint->name = $sprintName;
        $sprint->id_sprint = $sprint->insert();

        return $sprint;
    }

}
