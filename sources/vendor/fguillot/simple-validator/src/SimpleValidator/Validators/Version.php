<?php

namespace SimpleValidator\Validators;

use SimpleValidator\Base;

class Version extends Base
{
    public function execute(array $data)
    {
        if (isset($data[$this->field]) && $data[$this->field] !== '') {
            $pattern = '/^[0-9]+\.[0-9]+\.[0-9]+([+-][^+-][0-9A-Za-z-.]*)?$/';
            return (bool) preg_match($pattern, $data[$this->field]);
        }

        return true;
    }
}
