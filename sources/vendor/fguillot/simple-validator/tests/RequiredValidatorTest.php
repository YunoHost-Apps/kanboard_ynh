<?php

require_once 'src/SimpleValidator/Base.php';
require_once 'src/SimpleValidator/Validators/Required.php';

use SimpleValidator\Validators\Required;

class RequiredValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testValidator()
    {
        $message = 'field required';

        $v = new Required('toto', $message);
        
        $this->assertEquals($message, $v->getErrorMessage());

        $this->assertFalse($v->execute(array()));
        $this->assertFalse($v->execute(array('toto' => '')));
        $this->assertFalse($v->execute(array('toto' => null)));
        $this->assertTrue($v->execute(array('toto' => 0)));
        $this->assertTrue($v->execute(array('toto' => 'test')));
    }
}