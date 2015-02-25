<?php

namespace SimpleValidator\Validators;

use SimpleValidator\Base;

class MacAddress extends Base
{
    public function execute(array $data)
    {
        if (isset($data[$this->field]) && $data[$this->field] !== '') {

            $groups = explode(':', $data[$this->field]);

            if (count($groups) !== 6) {
                return false;
            }

            foreach ($groups as $group) {
                if (! ctype_xdigit($group)) {
                    return false;
                }
            }
        }

        return true;
    }
}
