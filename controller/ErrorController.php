<?php

class ErrorController extends Controller {
    public $layout = 'default.phtml';

    public function indexAction(Exception $e) {
        $this->view->error = $e;
        $this->view->errorInfo = 'no suggestion for solution :(';
        $this->result = array('error' => $e->getMessage()); //this is for ajax call

        if($e instanceof PDOException)
            $this->pdoException($e);
    }

    public function pdoException(PDOException $e) {
        if($e->getCode() == 'HY000')
            $this->view->errorInfo = 'Try to update your database with liqubase';
    }
}
