<?php

class Date {
    static public $dateTimeFormat = 'Y-m-d H:i:s';
    static public $dateFormat = 'Y-m-d';

    public $timestamp;

    public function __construct($time) {
        if(is_int($time)) {
            $this->timestamp = $time;
        } else {
            $this->timestamp = strtotime($time);
        }
    }

    public function shiftByMinutes($minutes) {
        $this->shiftBySeconds($minutes * 60);
        return $this;
    }

    public function shiftBySeconds($seconds) {
        $this->timestamp += $seconds;
        return $this;
    }

    public function toString() {
        return date(self::$dateTimeFormat, $this->timestamp);
    }

    public function __toString() {
        return $this->toString();
    }

    static public function getNow() {
        return new self(time());
    }

    static public function getCurrentDate() {
        return date(self::$dateFormat, time());
    }

}
