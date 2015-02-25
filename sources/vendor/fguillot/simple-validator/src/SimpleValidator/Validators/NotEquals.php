<?php

namespace SimpleValidator\Validators;

use SimpleValidator\Base;

/**
 * @author Olivier Maridat
 */
class NotEquals extends Base
{
    private $field2;

    public function __construct($field1, $field2, $error_message)
    {
        parent::__construct($field1, $error_message);

        $this->field2 = $field2;
    }

    public function execute(array $data)
    {
        if (isset($data[$this->field]) && $data[$this->field] !== '') {

            if (! isset($data[$this->field2])) {
                return true;
            }

            return $data[$this->field] !== $data[$this->field2];
        }

        return true;
    }
}
