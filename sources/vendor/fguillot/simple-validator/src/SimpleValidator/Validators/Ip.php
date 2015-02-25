<?php

namespace SimpleValidator\Validators;

use SimpleValidator\Base;

class Ip extends Base
{
    public function execute(array $data)
    {
        if (isset($data[$this->field]) && $data[$this->field] !== '') {
            if (! filter_var($data[$this->field], FILTER_VALIDATE_IP)) {
                return false;
            }
        }

        return true;
    }
}
