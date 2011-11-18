<?php

class TaskCollection extends Collection {
    public function getTodo() {
        $collection = new self;
        foreach($this as $task) {
            if($task->isTodo())
                $collection[] = $task;
        }
        return $collection;
    }

    public function getInProgress() {
        $collection = new self;
        foreach($this as $task) {
            if($task->isInProgress())
                $collection[] = $task;
        }
        return $collection;
    }

    public function getToVerify() {
        $collection = new self;
        foreach($this as $task) {
            if($task->isToVerify())
                $collection[] = $task;
        }
        return $collection;
    }

    public function getDone() {
        $collection = new self;
        foreach($this as $task) {
            if($task->isDone())
                $collection[] = $task;
        }
        return $collection;
    }

    public function getDetached() {
        $collection = new self;
        foreach($this as $task) {
            if($task->isDetached())
                $collection[] = $task;
        }
        return $collection;
    }

    public function getEstimationSum() {
        $sum = 0;
        foreach($this as $task) {
            $sum += $task->estimation;
        }
        return $sum;
    }

    public function getDoneSum() {
        $sum = 0;
        foreach($this as $task) {
            $sum += $task->done;
        }
        return $sum;
    }

    public function getAllTasksUpdateDates($format = null) {
        $dates = array();
        foreach($this as $task) {
            foreach($task->history as $history) {
                if($format)
                    $date = date($format, strtotime($history->date));
                else
                    $date = $history->date;
                $dates[strtotime($history->date)] = $date;
            }
        }
        ksort($dates);
        return array_unique($dates);
    }
}
