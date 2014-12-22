<?php

namespace JsonRPC;

use Closure;
use BadFunctionCallException;
use Exception;
use InvalidArgumentException;
use LogicException;
use ReflectionFunction;
use ReflectionMethod;

class InvalidJsonRpcFormat extends Exception {};
class InvalidJsonFormat extends Exception {};

/**
 * JsonRPC server class
 *
 * @package JsonRPC
 * @author  Frederic Guillot
 * @license Unlicense http://unlicense.org/
 */
class Server
{
    /**
     * Data received from the client
     *
     * @access private
     * @var string
     */
    private $payload;

    /**
     * List of procedures
     *
     * @static
     * @access private
     * @var array
     */
    private $callbacks = array();

    /**
     * List of classes
     *
     * @static
     * @access private
     * @var array
     */
    private $classes = array();

    /**
     * Constructor
     *
     * @access public
     * @param  string   $payload      Client data
     * @param  array    $callbacks    Callbacks
     * @param  array    $classes      Classes
     */
    public function __construct($payload = '', array $callbacks = array(), array $classes = array())
    {
        $this->payload = $payload;
        $this->callbacks = $callbacks;
        $this->classes = $classes;
    }

    /**
     * IP based client restrictions
     *
     * Return an HTTP error 403 if the client is not allowed
     *
     * @access public
     * @param  array   $hosts   List of hosts
     */
    public function allowHosts(array $hosts) {

        if (! in_array($_SERVER['REMOTE_ADDR'], $hosts)) {

            header('Content-Type: application/json');
            header('HTTP/1.0 403 Forbidden');
            echo '{"error": "Access Forbidden"}';
            exit;
        }
    }

    /**
     * HTTP Basic authentication
     *
     * Return an HTTP error 401 if the client is not allowed
     *
     * @access public
     * @param  array   $users   Map of username/password
     */
    public function authentication(array $users)
    {
        if (! isset($_SERVER['PHP_AUTH_USER']) ||
            ! isset($users[$_SERVER['PHP_AUTH_USER']]) ||
            $users[$_SERVER['PHP_AUTH_USER']] !== $_SERVER['PHP_AUTH_PW']) {

            header('WWW-Authenticate: Basic realm="JsonRPC"');
            header('Content-Type: application/json');
            header('HTTP/1.0 401 Unauthorized');
            echo '{"error": "Authentication failed"}';
            exit;
        }
    }

    /**
     * Register a new procedure
     *
     * @access public
     * @param  string   $procedure       Procedure name
     * @param  closure  $callback        Callback
     */
    public function register($name, Closure $callback)
    {
        $this->callbacks[$name] = $callback;
    }

    /**
     * Bind a procedure to a class
     *
     * @access public
     * @param  string   $procedure    Procedure name
     * @param  mixed    $class        Class name or instance
     * @param  string   $method       Procedure name
     */
    public function bind($procedure, $class, $method)
    {
        $this->classes[$procedure] = array($class, $method);
    }

    /**
     * Return the response to the client
     *
     * @access public
     * @param  array    $data      Data to send to the client
     * @param  array    $payload   Incoming data
     * @return string
     */
    public function getResponse(array $data, array $payload = array())
    {
        if (! array_key_exists('id', $payload)) {
            return '';
        }

        $response = array(
            'jsonrpc' => '2.0',
            'id' => $payload['id']
        );

        $response = array_merge($response, $data);

        @header('Content-Type: application/json');
        return json_encode($response);
    }

    /**
     * Parse the payload and test if the parsed JSON is ok
     *
     * @access public
     */
    public function checkJsonFormat()
    {
        if (empty($this->payload)) {
            $this->payload = file_get_contents('php://input');
        }

        if (is_string($this->payload)) {
            $this->payload = json_decode($this->payload, true);
        }

        if (! is_array($this->payload)) {
            throw new InvalidJsonFormat('Malformed payload');
        }
    }

    /**
     * Test if all required JSON-RPC parameters are here
     *
     * @access public
     */
    public function checkRpcFormat()
    {
        if (! isset($this->payload['jsonrpc']) ||
            ! isset($this->payload['method']) ||
            ! is_string($this->payload['method']) ||
            $this->payload['jsonrpc'] !== '2.0' ||
            (isset($this->payload['params']) && ! is_array($this->payload['params']))) {

            throw new InvalidJsonRpcFormat('Invalid JSON RPC payload');
        }
    }

    /**
     * Return true if we have a batch request
     *
     * @access public
     * @return boolean
     */
    private function isBatchRequest()
    {
        return array_keys($this->payload) === range(0, count($this->payload) - 1);
    }

    /**
     * Handle batch request
     *
     * @access private
     * @return string
     */
    private function handleBatchRequest()
    {
        $responses = array();

        foreach ($this->payload as $payload) {

            if (! is_array($payload)) {

                $responses[] = $this->getResponse(array(
                    'error' => array(
                        'code' => -32600,
                        'message' => 'Invalid Request'
                    )),
                    array('id' => null)
                );
            }
            else {

                $server = new Server($payload, $this->callbacks, $this->classes);
                $response = $server->execute();

                if ($response) {
                    $responses[] = $response;
                }
            }
        }

        return empty($responses) ? '' : '['.implode(',', $responses).']';
    }

    /**
     * Parse incoming requests
     *
     * @access public
     * @return string
     */
    public function execute()
    {
        try {

            $this->checkJsonFormat();

            if ($this->isBatchRequest()){
                return $this->handleBatchRequest();
            }

            $this->checkRpcFormat();

            $result = $this->executeProcedure(
                $this->payload['method'],
                empty($this->payload['params']) ? array() : $this->payload['params']
            );

            return $this->getResponse(array('result' => $result), $this->payload);
        }
        catch (InvalidJsonFormat $e) {

            return $this->getResponse(array(
                'error' => array(
                    'code' => -32700,
                    'message' => 'Parse error'
                )),
                array('id' => null)
            );
        }
        catch (InvalidJsonRpcFormat $e) {

            return $this->getResponse(array(
                'error' => array(
                    'code' => -32600,
                    'message' => 'Invalid Request'
                )),
                array('id' => null)
            );
        }
        catch (BadFunctionCallException $e) {

            return $this->getResponse(array(
                'error' => array(
                    'code' => -32601,
                    'message' => 'Method not found'
                )),
                $this->payload
            );
        }
        catch (InvalidArgumentException $e) {

            return $this->getResponse(array(
                'error' => array(
                    'code' => -32602,
                    'message' => 'Invalid params'
                )),
                $this->payload
            );
        }
    }

    /**
     * Execute the procedure
     *
     * @access public
     * @param  string   $procedure    Procedure name
     * @param  array    $params       Procedure params
     * @return mixed
     */
    public function executeProcedure($procedure, array $params = array())
    {
        if (isset($this->callbacks[$procedure])) {
            return $this->executeCallback($this->callbacks[$procedure], $params);
        }
        else if (isset($this->classes[$procedure])) {
            return $this->executeMethod($this->classes[$procedure][0], $this->classes[$procedure][1], $params);
        }

        throw new BadFunctionCallException('Unable to find the procedure');
    }

    /**
     * Execute a callback
     *
     * @access public
     * @param  Closure   $callback     Callback
     * @param  array     $params       Procedure params
     * @return mixed
     */
    public function executeCallback(Closure $callback, $params)
    {
        $reflection = new ReflectionFunction($callback);

        $arguments = $this->getArguments(
            $params,
            $reflection->getParameters(),
            $reflection->getNumberOfRequiredParameters(),
            $reflection->getNumberOfParameters()
        );

        return $reflection->invokeArgs($arguments);
    }

    /**
     * Execute a method
     *
     * @access public
     * @param  mixed     $class        Class name or instance
     * @param  string    $method       Method name
     * @param  array     $params       Procedure params
     * @return mixed
     */
    public function executeMethod($class, $method, $params)
    {
        $reflection = new ReflectionMethod($class, $method);

        $arguments = $this->getArguments(
            $params,
            $reflection->getParameters(),
            $reflection->getNumberOfRequiredParameters(),
            $reflection->getNumberOfParameters()
        );

        return $reflection->invokeArgs(
            is_string($class) ? new $class : $class,
            $arguments
        );
    }

    /**
     * Get procedure arguments
     *
     * @access public
     * @param  array    $request_params       Incoming arguments
     * @param  array    $method_params        Procedure arguments
     * @param  integer  $nb_required_params   Number of required parameters
     * @param  integer  $nb_max_params        Maximum number of parameters
     * @return array
     */
    public function getArguments(array $request_params, array $method_params, $nb_required_params, $nb_max_params)
    {
        $nb_params = count($request_params);

        if ($nb_params < $nb_required_params) {
            throw new InvalidArgumentException('Wrong number of arguments');
        }

        if ($nb_params > $nb_max_params) {
            throw new InvalidArgumentException('Too many arguments');
        }

        if ($this->isPositionalArguments($request_params, $method_params)) {
            return $request_params;
        }

        return $this->getNamedArguments($request_params, $method_params);
    }

    /**
     * Return true if we have positional parametes
     *
     * @access public
     * @param  array    $request_params      Incoming arguments
     * @param  array    $method_params       Procedure arguments
     * @return bool
     */
    public function isPositionalArguments(array $request_params, array $method_params)
    {
        return array_keys($request_params) === range(0, count($request_params) - 1);
    }

    /**
     * Get named arguments
     *
     * @access public
     * @param  array    $request_params      Incoming arguments
     * @param  array    $method_params       Procedure arguments
     * @return array
     */
    public function getNamedArguments(array $request_params, array $method_params)
    {
        $params = array();

        foreach ($method_params as $p) {

            $name = $p->getName();

            if (isset($request_params[$name])) {
                $params[$name] = $request_params[$name];
            }
            else if ($p->isDefaultValueAvailable()) {
                $params[$name] = $p->getDefaultValue();
            }
            else {
                throw new InvalidArgumentException('Missing argument: '.$name);
            }
        }

        return $params;
    }
}
