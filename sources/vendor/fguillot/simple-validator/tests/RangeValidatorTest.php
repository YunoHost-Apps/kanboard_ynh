<?php

require_once 'src/SimpleValidator/Base.php';
require_once 'src/SimpleValidator/Validators/Range.php';

use SimpleValidator\Validators\Range;

class RangeValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testValidator()
    {
        $message = 'field must be between 1 and 3.14';

        $v = new Range('toto', $message, -1, 3.14);

        $this->assertEquals($message, $v->getErrorMessage());

        $this->assertTrue($v->execute(array('toto' => '')));
        $this->assertTrue($v->execute(array('toto' => null)));
        $this->assertFalse($v->execute(array('toto' => 'ddgg')));
        $this->assertFalse($v->execute(array('toto' => '123.4')));
        $this->assertFalse($v->execute(array('toto' => 123.4)));
        $this->assertFalse($v->execute(array('toto' => -2)));

        $this->assertTrue($v->execute(array()));
        $this->assertTrue($v->execute(array('toto' => 3.14)));
        $this->assertTrue($v->execute(array('toto' => 1)));
        $this->assertTrue($v->execute(array('toto' => '1.25')));
        $this->assertTrue($v->execute(array('toto' => '-0.5')));
        $this->assertTrue($v->execute(array('toto' => 0)));
        $this->assertTrue($v->execute(array('toto' => -1)));
        $this->assertTrue($v->execute(array('toto' => '0')));
    }
}