<?php

class IndexController extends ScrumieController {

    protected $layout = 'default.phtml';

    public function indexAction() {
        if($this->isLogged())
            $this->_redirect('Board', 'index');
    }
}
