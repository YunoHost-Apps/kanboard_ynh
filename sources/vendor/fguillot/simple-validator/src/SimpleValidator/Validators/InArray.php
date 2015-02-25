<?php

namespace SimpleValidator\Validators;

use SimpleValidator\Base;

class InArray extends Base
{
    protected $array;

    public function __construct($field, array $array, $error_message)
    {
        parent::__construct($field, $error_message);

        $this->array = $array;
    }

    public function execute(array $data)
    {
        if (array_key_exists($this->field, $this->array)) {
            return in_array($data[$this->field], $this->array);
        }

        return true;
    }
}
