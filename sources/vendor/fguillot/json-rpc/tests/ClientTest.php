<?php

require_once 'src/JsonRPC/Client.php';

use JsonRPC\Client;

class ClientTest extends PHPUnit_Framework_TestCase
{
    public function testParseReponse()
    {
        $client = new Client('http://localhost/');

        $this->assertEquals(
            -19,
            $client->parseResponse(json_decode('{"jsonrpc": "2.0", "result": -19, "id": 1}', true))
        );

        $this->assertEquals(
            null,
            $client->parseResponse(json_decode('{"jsonrpc": "2.0", "id": 1}', true))
        );
    }

    /**
     * @expectedException BadFunctionCallException
     */
    public function testBadProcedure()
    {
        $client = new Client('http://localhost/');
        $client->parseResponse(json_decode('{"jsonrpc": "2.0", "error": {"code": -32601, "message": "Method not found"}, "id": "1"}', true));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgs()
    {
        $client = new Client('http://localhost/');
        $client->parseResponse(json_decode('{"jsonrpc": "2.0", "error": {"code": -32602, "message": "Invalid params"}, "id": "1"}', true));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testInvalidRequest()
    {
        $client = new Client('http://localhost/');
        $client->parseResponse(json_decode('{"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null}', true));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testParseError()
    {
        $client = new Client('http://localhost/');
        $client->parseResponse(json_decode('{"jsonrpc": "2.0", "error": {"code": -32700, "message": "Parse error"}, "id": null}', true));
    }

    public function testPrepareRequest()
    {
        $client = new Client('http://localhost/');

        $payload = $client->prepareRequest('myProcedure');
        $this->assertNotEmpty($payload);
        $this->assertArrayHasKey('jsonrpc', $payload);
        $this->assertEquals('2.0', $payload['jsonrpc']);
        $this->assertArrayHasKey('method', $payload);
        $this->assertEquals('myProcedure', $payload['method']);
        $this->assertArrayHasKey('id', $payload);
        $this->assertArrayNotHasKey('params', $payload);

        $payload = $client->prepareRequest('myProcedure', array('p1' => 3));
        $this->assertNotEmpty($payload);
        $this->assertArrayHasKey('jsonrpc', $payload);
        $this->assertEquals('2.0', $payload['jsonrpc']);
        $this->assertArrayHasKey('method', $payload);
        $this->assertEquals('myProcedure', $payload['method']);
        $this->assertArrayHasKey('id', $payload);
        $this->assertArrayHasKey('params', $payload);
        $this->assertEquals(array('p1' => 3), $payload['params']);
    }

    public function testBatchRequest()
    {
        $client = new Client('http://localhost/');

        $batch = $client->batch();

        $this->assertInstanceOf('JsonRpc\Client', $batch);
        $this->assertTrue($client->is_batch);

        $batch->random(1, 30);
        $batch->add(3, 5);
        $batch->execute('foo', array('p1' => 42, 'p3' => 3));

        $this->assertNotEmpty($client->batch);
        $this->assertEquals(3, count($client->batch));

        $this->assertEquals('random', $client->batch[0]['method']);
        $this->assertEquals('add', $client->batch[1]['method']);
        $this->assertEquals('foo', $client->batch[2]['method']);

        $this->assertEquals(array(1, 30), $client->batch[0]['params']);
        $this->assertEquals(array(3, 5), $client->batch[1]['params']);
        $this->assertEquals(array('p1' => 42, 'p3' => 3), $client->batch[2]['params']);

        $batch = $client->batch();

        $this->assertInstanceOf('JsonRpc\Client', $batch);
        $this->assertTrue($client->is_batch);
        $this->assertEmpty($client->batch);
    }
}