<?php

require_once 'src/SimpleValidator/Base.php';
require_once 'src/SimpleValidator/Validators/Unique.php';

use SimpleValidator\Validators\Unique;

class UniqueValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testValidator()
    {
    	$pdo = new PDO('sqlite::memory:');
    	$pdo->exec('CREATE TABLE mytable (id INTEGER, toto TEXT)');

        $message = 'field must be unique';

        $v = new Unique('toto', $message, $pdo, 'mytable');

        $this->assertEquals($message, $v->getErrorMessage());

        $this->assertTrue($v->execute(array('toto' => '')));
        $this->assertTrue($v->execute(array('toto' => null)));

        $this->assertTrue($v->execute(array('toto' => 'titi')));

        $pdo->exec("INSERT INTO mytable VALUES ('1', 'truc')");

        $this->assertTrue($v->execute(array('toto' => 'titi')));

        $pdo->exec("INSERT INTO mytable VALUES ('2', 'titi')");

        $this->assertFalse($v->execute(array('toto' => 'titi')));
        
        $this->assertTrue($v->execute(array('toto' => 'titi', 'id' => '2')));

        $this->assertFalse($v->execute(array('toto' => 'truc', 'id' => '2')));
    }
}