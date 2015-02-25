<?php

namespace SimpleValidator\Validators;

use SimpleValidator\Base;

class MinLength extends Base
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

            $length = mb_strlen($data[$this->field], 'UTF-8');

            if ($length < $this->min) {
                return false;
            }
        }

        return true;
    }
}
