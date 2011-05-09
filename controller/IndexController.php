<?php

class IndexController extends AppController {

    public $layout = 'default.phtml';

    public function indexAction() {
        if($this->isLogged())
            $this->redirect('Board', 'index');
    }

    public function keepAliveAction() {
        $this->result = true;
    }
}
