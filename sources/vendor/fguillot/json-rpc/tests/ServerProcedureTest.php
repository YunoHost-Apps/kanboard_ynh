<?php

require_once 'src/JsonRPC/Server.php';

use JsonRPC\Server;

class A
{
    public function getAll($p1, $p2, $p3 = 4)
    {
        return $p1 + $p2 + $p3;
    }
}

class B
{
    public function getAll($p1)
    {
        return $p1 + 2;
    }
}

class ServerProcedureTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException BadFunctionCallException
     */
    public function testProcedureNotFound()
    {
        $server = new Server;
        $server->executeProcedure('a');
    }

    /**
     * @expectedException BadFunctionCallException
     */
    public function testCallbackNotFound()
    {
        $server = new Server;
        $server->register('b', function() {});
        $server->executeProcedure('a');
    }

    /**
     * @expectedException ReflectionException
     */
    public function testClassNotFound()
    {
        $server = new Server;
        $server->bind('getAllTasks', 'c', 'getAll');
        $server->executeProcedure('getAllTasks');
    }

    /**
     * @expectedException ReflectionException
     */
    public function testMethodNotFound()
    {
        $server = new Server;
        $server->bind('getAllTasks', 'A', 'getNothing');
        $server->executeProcedure('getAllTasks');
    }

    public function testIsPositionalArguments()
    {
        $server = new Server;
        $this->assertFalse($server->isPositionalArguments(
            array('a' => 'b', 'c' => 'd'),
            array('a' => 'b', 'c' => 'd')
        ));

        $server = new Server;
        $this->assertTrue($server->isPositionalArguments(
            array('a', 'b', 'c'),
            array('a' => 'b', 'c' => 'd')
        ));
    }

    public function testBindNamedArguments()
    {
        $server = new Server;
        $server->bind('getAllA', 'A', 'getAll');
        $server->bind('getAllB', 'B', 'getAll');
        $server->bind('getAllC', new B, 'getAll');
        $this->assertEquals(6, $server->executeProcedure('getAllA', array('p2' => 4, 'p1' => -2)));
        $this->assertEquals(10, $server->executeProcedure('getAllA', array('p2' => 4, 'p3' => 8, 'p1' => -2)));
        $this->assertEquals(6, $server->executeProcedure('getAllB', array('p1' => 4)));
        $this->assertEquals(5, $server->executeProcedure('getAllC', array('p1' => 3)));
    }

    public function testBindPositionalArguments()
    {
        $server = new Server;
        $server->bind('getAllA', 'A', 'getAll');
        $server->bind('getAllB', 'B', 'getAll');
        $this->assertEquals(6, $server->executeProcedure('getAllA', array(4, -2)));
        $this->assertEquals(2, $server->executeProcedure('getAllA', array(4, 0, -2)));
        $this->assertEquals(4, $server->executeProcedure('getAllB', array(2)));
    }

    public function testRegisterNamedArguments()
    {
        $server = new Server;
        $server->register('getAllA', function($p1, $p2, $p3 = 4) {
            return $p1 + $p2 + $p3;
        });

        $this->assertEquals(6, $server->executeProcedure('getAllA', array('p2' => 4, 'p1' => -2)));
        $this->assertEquals(10, $server->executeProcedure('getAllA', array('p2' => 4, 'p3' => 8, 'p1' => -2)));
    }

    public function testRegisterPositionalArguments()
    {
        $server = new Server;
        $server->register('getAllA', function($p1, $p2, $p3 = 4) {
            return $p1 + $p2 + $p3;
        });

        $this->assertEquals(6, $server->executeProcedure('getAllA', array(4, -2)));
        $this->assertEquals(2, $server->executeProcedure('getAllA', array(4, 0, -2)));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testTooManyArguments()
    {
        $server = new Server;
        $server->bind('getAllC', new B, 'getAll');
        $server->executeProcedure('getAllC', array('p1' => 3, 'p2' => 5));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNotEnoughArguments()
    {
        $server = new Server;
        $server->bind('getAllC', new B, 'getAll');
        $server->executeProcedure('getAllC');
    }
}
