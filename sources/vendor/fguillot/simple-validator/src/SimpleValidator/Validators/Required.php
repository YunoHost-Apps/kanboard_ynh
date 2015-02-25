<?php

namespace SimpleValidator\Validators;

use SimpleValidator\Base;

class Required extends Base
{
    public function execute(array $data)
    {
        if (! isset($data[$this->field]) || $data[$this->field] === '') {
            return false;
        }

        return true;
    }
}
