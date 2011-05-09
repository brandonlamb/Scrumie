<?php

class CreateProject extends ClixTask {
    const HINT = 'Create new project with default template';

    public $params_config = '{ 
        "name": {
            "description": "Name of new project (it will be name of project directory)",
            "mandatory": true
        }
    }';

    public function execute() {
        $dest = ROOT_DIR . '/project/' . $this->name;
        $src = ROOT_DIR . '/project/.skeleton';

        if(is_dir($dest))
            Clix::stop('Directory name %s already exists', $dest);

        Clix::message('Creating project %s', $dest);
        $this->recurse_copy($src, $dest);
        Clix::message('Done');
    }

    
    //@source http://php.net/manual/en/function.copy.php
    public function recurse_copy($src,$dst) { 
        $dir = opendir($src); 
        @mkdir($dst); 

        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    } 
}
