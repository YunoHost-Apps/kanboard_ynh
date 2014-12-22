<?php

require_once 'src/SimpleValidator/Base.php';
require_once 'src/SimpleValidator/Validator.php';
require_once 'src/SimpleValidator/Validators/Integer.php';
require_once 'src/SimpleValidator/Validators/Numeric.php';
require_once 'src/SimpleValidator/Validators/Required.php';

use SimpleValidator\Validator;
use SimpleValidator\Validators;

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testValidator()
    {
    	$data = array();

    	$v = new Validator($data, array(
    		new Validators\Required('toto', 'toto is required'),
    		new Validators\Integer('toto', 'toto must be an integer'),
    	));

    	$this->assertFalse($v->execute());

    	$this->assertEquals(
    		array(
	    		'toto' => array(
	    			'toto is required',
	    		)
    		),
    		$v->getErrors()
    	);

    	$data = array('toto' => 'bla');

    	$v = new Validator($data, array(
    		new Validators\Required('toto', 'toto is required'),
    		new Validators\Integer('toto', 'toto must be an integer'),
    		new Validators\Range('toto', 'toto is out of range', 1, 10),
    	));

    	$this->assertFalse($v->execute());

    	$this->assertEquals(
    		array(
	    		'toto' => array(
	    			'toto must be an integer',
	    			'toto is out of range'
	    		)
    		),
    		$v->getErrors()
    	);

    	$data = array('toto' => 11);

    	$v = new Validator($data, array(
    		new Validators\Required('toto', 'toto is required'),
    		new Validators\Integer('toto', 'toto must be an integer'),
    		new Validators\Range('toto', 'toto is out of range', 1, 10),
    	));

    	$this->assertFalse($v->execute());

    	$this->assertEquals(
    		array(
	    		'toto' => array(
	    			'toto is out of range'
	    		)
    		),
    		$v->getErrors()
    	);

    	$data = array('toto' => '5');

    	$v = new Validator($data, array(
    		new Validators\Required('toto', 'toto is required'),
    		new Validators\Integer('toto', 'toto must be an integer'),
    		new Validators\Range('toto', 'toto is out of range', 1, 10),
    	));

    	$this->assertTrue($v->execute());

    	$this->assertEquals(
    		array(),
    		$v->getErrors()
    	);

    	$data = array('toto' => '');

    	$v = new Validator($data, array(
    		new Validators\Integer('toto', 'toto must be an integer')
    	));

    	$this->assertTrue($v->execute());

    	$this->assertEquals(
    		array(),
    		$v->getErrors()
    	);

    	$data = array('toto' => '55');

    	$v = new Validator($data, array(
    		new Validators\Integer('toto', 'toto must be an integer')
    	));

    	$this->assertTrue($v->execute());

    	$this->assertEquals(
    		array(),
    		$v->getErrors()
    	);

    	$data = array('toto' => 'hh');

    	$v = new Validator($data, array(
    		new Validators\Integer('toto', 'toto must be an integer')
    	));

    	$this->assertFalse($v->execute());

    	$this->assertEquals(
    		array(
    			'toto' => array(
	    			'toto must be an integer',
	    		)
    		),
    		$v->getErrors()
    	);
    }
}