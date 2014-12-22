<?php

require_once 'src/SimpleValidator/Base.php';
require_once 'src/SimpleValidator/Validators/Integer.php';

use SimpleValidator\Validators\Integer;

class IntegerValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testValidator()
    {
        $message = 'field must be an integer';

        $v = new Integer('toto', $message);

        $this->assertEquals($message, $v->getErrorMessage());

        $this->assertTrue($v->execute(array('toto' => '')));
        $this->assertTrue($v->execute(array('toto' => null)));
        $this->assertFalse($v->execute(array('toto' => 'ddgg')));
        $this->assertFalse($v->execute(array('toto' => '123.4')));
        $this->assertFalse($v->execute(array('toto' => 123.4)));
        $this->assertTrue($v->execute(array()));
        $this->assertTrue($v->execute(array('toto' => 123)));
        $this->assertTrue($v->execute(array('toto' => -123)));
        $this->assertTrue($v->execute(array('toto' => '-123')));
        $this->assertTrue($v->execute(array('toto' => 0)));
        $this->assertTrue($v->execute(array('toto' => '0')));
    }
}