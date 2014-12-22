<?php

require_once 'src/SimpleValidator/Base.php';
require_once 'src/SimpleValidator/Validators/Email.php';

use SimpleValidator\Validators\Email;

class EmailValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testValidator()
    {
        $message = 'bad email address';

        $v = new Email('toto', $message);

        $this->assertEquals($message, $v->getErrorMessage());
        
        $this->assertFalse($v->execute(array('toto' => '@')));
        $this->assertFalse($v->execute(array('toto' => '.')));
        $this->assertFalse($v->execute(array('toto' => 'vb@fgfg.')));
        $this->assertFalse($v->execute(array('toto' => 'v€b@fgfg')));
        $this->assertFalse($v->execute(array('toto' => 'user')));
        $this->assertFalse($v->execute(array('toto' => 'user@')));
        $this->assertFalse($v->execute(array('toto' => 'user@g')));
        $this->assertFalse($v->execute(array('toto' => 'user@.domain')));
        $this->assertFalse($v->execute(array('toto' => 'user@do..main')));
        $this->assertFalse($v->execute(array('toto' => 'user@domain|subdomain')));

        $this->assertTrue($v->execute(array()));
        $this->assertTrue($v->execute(array('toto' => '')));
        $this->assertTrue($v->execute(array('toto' => null)));
        $this->assertTrue($v->execute(array('toto' => 'toto+truc@machin.local')));
        $this->assertTrue($v->execute(array('toto' => 'toto+truc@machin-bidule')));
    }
}