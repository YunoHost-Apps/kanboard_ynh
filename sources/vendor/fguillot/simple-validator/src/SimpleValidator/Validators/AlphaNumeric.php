<?php

namespace SimpleValidator\Validators;

use SimpleValidator\Base;

class AlphaNumeric extends Base
{
    public function execute(array $data)
    {
        if (isset($data[$this->field]) && $data[$this->field] !== '') {

            if (! ctype_alnum($data[$this->field])) {
                return false;
            }
        }

        return true;
    }
}
