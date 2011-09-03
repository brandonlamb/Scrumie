<?php

class TaskApi extends Api
{
    public function getTasksUpdateDates(array $tasks_ids) {
        $dates = array();

        if(! $tasks_ids)
            return $dates;

        foreach(DAO::get('TaskHistory')->by('id_task', $tasks_ids, array('date ASC')) as $data) {
            if(!in_array($data->date, $dates))
                $dates[] = $data->date;
        }

        return $dates;
    }
}
