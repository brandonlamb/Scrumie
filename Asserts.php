<?php

class AssertsException extends InvalidArgumentException {};
class Asserts {
    static public function hasModelValues(DataModel $model, array $values) {
        foreach($values as $field => $value) {
            if($model->$field != $value)
                throw new AssertsException(sprintf('Property %s->%s is not equal %s', get_class($model), $field, $value));
        }
    }
}
