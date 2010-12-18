<?php

abstract class Controller {
    protected $View;
    protected $name;
    protected $action;

    final public function __construct($action) {
        $this->View = new View;
        $this->View->setTemplateFile($action.'.phtml');
        $this->View->setTemplateDir('./view/'.$this->name);

        $this->init();
    }

    protected function init() { }

    public function flush() {
        echo (string) $this->View;
    }
}
