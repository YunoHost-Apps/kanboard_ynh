<?php

require_once 'src/SimpleValidator/Base.php';
require_once 'src/SimpleValidator/Validators/Length.php';

use SimpleValidator\Validators\Length;

class LengthValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testValidator()
    {
        $message = 'field must be between 3 and 8 chars';

        $v = new Length('toto', $message, 3, 8);

        $this->assertEquals($message, $v->getErrorMessage());

        $this->assertTrue($v->execute(array('toto' => '')));
        $this->assertTrue($v->execute(array('toto' => null)));
        $this->assertFalse($v->execute(array('toto' => 'dd')));
        $this->assertFalse($v->execute(array('toto' => '123456789')));
        $this->assertFalse($v->execute(array('toto' => -2)));

        $this->assertTrue($v->execute(array()));
        $this->assertTrue($v->execute(array('toto' => 3.14)));
        $this->assertTrue($v->execute(array('toto' => 123)));
        $this->assertTrue($v->execute(array('toto' => '1.25')));
        $this->assertTrue($v->execute(array('toto' => '-0.5')));
        $this->assertTrue($v->execute(array('toto' => '12345678')));
    }
}