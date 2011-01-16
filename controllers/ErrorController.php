<?php

class ErrorController extends Controller {
    protected $layout = 'default.phtml';

    public function index(Exception $e) {
        $this->view->error = $e;
        $this->result = array('error' => $e->getMessage()); //this is for ajax call
    }
}
