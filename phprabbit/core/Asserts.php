<?php

class AssertsException extends ApplicationException {}
class Asserts {
    static public function isString($value) {
        if(!is_string($value))
            self::throwException('Value %s is not string', gettype($value));
    }

    static public function isEmptyString($value) {
        self::isString($value);

        if($value === '')
            self::throwException('Value <%s> must be empty string', $value);
    }

    static public function isNonEmptyString($value) {
        self::isString($value);

        if($value !== '')
            self::throwException('Value <%s> can\'t be empty string', $value);
    }

    static protected function throwException() {
        $msg = call_user_func_array('sprintf', func_get_args());
        throw new AssertsException($msg);
    }
}

