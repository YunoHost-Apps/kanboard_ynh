<?php

/*
 * This file is part of Simple Validator.
 *
 * (c) Frédéric Guillot <contact@fredericguillot.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SimpleValidator\Validators;

use SimpleValidator\Base;

class InArray extends Base
{
    protected $array;

    public function __construct($field, array $array, $error_message)
    {
        parent::__construct($field, $error_message);

        $this->array = $array;
    }

    public function execute(array $data)
    {
        if (array_key_exists($this->field, $this->array)) {
            return in_array($data[$this->field], $this->array);
        }

        return true;
    }
}
