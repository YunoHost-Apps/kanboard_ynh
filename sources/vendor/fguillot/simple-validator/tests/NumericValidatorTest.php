<?php

require_once 'src/SimpleValidator/Base.php';
require_once 'src/SimpleValidator/Validators/Numeric.php';

use SimpleValidator\Validators\Numeric;

class NumericValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testValidator()
    {
        $message = 'field must be numeric';

        $v = new Numeric('toto', $message);

        $this->assertEquals($message, $v->getErrorMessage());

        $this->assertTrue($v->execute(array('toto' => '')));
        $this->assertTrue($v->execute(array('toto' => null)));
        $this->assertFalse($v->execute(array('toto' => 'ddgg')));
        $this->assertTrue($v->execute(array()));
        $this->assertTrue($v->execute(array('toto' => '123.4')));
        $this->assertTrue($v->execute(array('toto' => 123)));
        $this->assertTrue($v->execute(array('toto' => 123.4)));
        $this->assertTrue($v->execute(array('toto' => 0)));
    }
}