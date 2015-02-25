<?php

namespace SimpleValidator\Validators;

use SimpleValidator\Base;

class Numeric extends Base
{
    public function execute(array $data)
    {
        if (isset($data[$this->field]) && $data[$this->field] !== '') {

            if (! is_numeric($data[$this->field])) {
                return false;
            }
        }

        return true;
    }
}
