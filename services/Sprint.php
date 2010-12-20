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

}
