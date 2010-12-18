<?php

class IndexController extends Controller {
    protected $name = 'index';
    public function indexAction() {
        $this->View->login = 'xxxx';
    }

    public function nextAction() {
        //to run this action enter ?controller=Index&action=next
    }
}
