<?php

class AssertsException extends ApplicationException {}
class Asserts {
    static public function isEmptyString($value) {
        if(!is_string($value))
            self::throwException('Value %s is not string', gettype($value));

        if($value === '')
            self::throwException('Value "%s" is not empty string');
    }

    static protected function throwException($msg) {
        $msg = call_user_func_array('sprintf', func_get_args());
        throw new AssertsException($msg);
    }
}

