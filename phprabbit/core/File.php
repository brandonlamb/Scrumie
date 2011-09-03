<?php

class File {
    static public function save($path, $data, $mode = 'w') {
        $f = fopen($path, $mode);
        fwrite($f, $data);
        fclose($f);
    }

    static public function read($path) {
        if(!is_file($path) || !is_readable($path)) {
            return false;
        }

        return file_get_contents($path);
    }

    static public function delete($path) {
        unlink($path);
    }
}
