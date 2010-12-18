<?php

class View {
    protected $_template_dir;
    protected $_template_file;

    final function __construct() {
    }

    public function __toString() {
        return $this->render();
    }
    
    protected function render() {
        ob_start();
        include($this->getTemplateFilePath());
        $body = ob_get_contents();
        ob_end_clean();
        return $body;
    }

    protected function getTemplateFilePath() {
        return $this->_template_dir.'/'.$this->_template_file;
    }

    public function __set($name, $value) {
        if($name == '_template_dir' || $name == '_template_file')
            throw new InvalidArgumentException(sprintf('Variable name %s in View is restricted',$name));

        $this->$name = $value;
    }

    public function setTemplateFile($filename) {
        $this->_template_file = $filename;
    }

    public function setTemplateDir($path) {
        $this->_template_dir = $path;
    }
}
