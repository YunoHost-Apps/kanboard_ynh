<?php

namespace SimpleValidator\Validators;

class NotInArray extends InArray
{
    public function execute(array $data)
    {
        if (array_key_exists($this->field, $this->array)) {
            return !in_array($data[$this->field], $this->array);
        }

        return true;
    }
}
