<?php

namespace SimpleValidator\Validators;

use SimpleValidator\Base;

class Alpha extends Base
{
    public function execute(array $data)
    {
        if (isset($data[$this->field]) && $data[$this->field] !== '') {

            if (! ctype_alpha($data[$this->field])) {
                return false;
            }
        }

        return true;
    }
}
