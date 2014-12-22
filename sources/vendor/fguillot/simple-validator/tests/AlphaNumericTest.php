<?php

require_once 'src/SimpleValidator/Base.php';
require_once 'src/SimpleValidator/Validators/AlphaNumeric.php';

use SimpleValidator\Validators\AlphaNumeric;

class AlphaNumericValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testValidator()
    {
        $message = 'field must be numeric';

        $v = new AlphaNumeric('toto', $message);

        $this->assertEquals($message, $v->getErrorMessage());

        $this->assertTrue($v->execute(array('toto' => '')));
        $this->assertTrue($v->execute(array('toto' => null)));
        $this->assertTrue($v->execute(array('toto' => 'abc123')));
        $this->assertTrue($v->execute(array()));
        $this->assertFalse($v->execute(array('toto' => '123.4')));
        $this->assertFalse($v->execute(array('toto' => 123)));
        $this->assertFalse($v->execute(array('toto' => 123)));
        $this->assertFalse($v->execute(array('toto' => 'hjh-hjh')));
    }
}
