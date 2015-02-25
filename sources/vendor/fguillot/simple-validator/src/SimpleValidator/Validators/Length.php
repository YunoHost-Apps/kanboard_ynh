<?php

namespace SimpleValidator\Validators;

use SimpleValidator\Base;

class Length extends Base
{
    private $min;
    private $max;

    public function __construct($field, $error_message, $min, $max)
    {
        parent::__construct($field, $error_message);

        $this->min = $min;
        $this->max = $max;
    }

    public function execute(array $data)
    {
        if (isset($data[$this->field]) && $data[$this->field] !== '') {

            $length = mb_strlen($data[$this->field], 'UTF-8');

            if ($length < $this->min || $length > $this->max) {
                return false;
            }
        }

        return true;
    }
}
