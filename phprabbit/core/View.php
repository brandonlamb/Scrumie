<?php

class ViewException extends Exception {}
class View {
    protected $_template_dir;
    protected $_template_file;

    final function __construct() {
    }

    public function __get($name) {
        return null;
    }

    public function __toString() {
        try {
            return $this->render();
        } catch (Exception $e) {
            return $e->getTraceAsString();
        }
    }
    
    public function render() {
        if(!is_file($template = $this->getTemplateFilePath())) {
            throw new ViewException("Template file '$template' doesn't exists");
        }
        ob_start();
        include($this->getTemplateFilePath());
        $body = ob_get_contents();
        ob_end_clean();
        return $body;
    }

    public function getTemplateFilePath() {
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

    public function _echo($body, $model) {
        $properties = get_object_vars($model); 
        foreach($properties as $key => $value) {
            if(is_scalar($value))
                $body = str_replace('{'.$key.'}', $value, $body);
        }
        echo $body;
    }

    public function getPublicDir() {
        return 'http://'.$_SERVER['HTTP_HOST'].'/public/';
    }

    public function css($name) {
        if ( preg_match('#http[s]?://#', $name) ) {
            echo '<link href="'.$name.'" rel="stylesheet" type="text/css"/>';
        } else  {
            echo '<link href="'.($this->getPublicDir().$name).'" rel="stylesheet" type="text/css"/>';
        }
    }

    public function favicon($name) {
        if ( preg_match('#http[s]?://#', $name) ) {
            echo '<link href="'.$name.'" rel="shortcut icon"/>';
        } else {
            echo '<link href="'.($this->getPublicDir().$name).'" rel="shortcut icon"/>';
        }
    }

    public function script($name) {
        if ( preg_match('#http[s]?://#', $name) ) {
            echo '<script type="text/javascript" src="'.$name.'"></script>';
        } else {
            echo '<script type="text/javascript" src="'.($this->getPublicDir().$name).'"></script>';
        }
    }
}
