<?php

namespace SimpleValidator\Validators;

use SimpleValidator\Base;

class GreaterThan extends Base
{
    private $min;

    public function __construct($field, $error_message, $min)
    {
        parent::__construct($field, $error_message);
        $this->min = $min;
    }

    public function execute(array $data)
    {
        if (isset($data[$this->field]) && $data[$this->field] !== '') {
            return $data[$this->field] > $this->min;
        }

        return true;
    }
}
