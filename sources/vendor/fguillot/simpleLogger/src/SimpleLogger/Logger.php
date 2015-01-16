<?php

namespace SimpleLogger;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Handler for multiple loggers
 *
 * @package SimpleLogger
 * @author  Frédéric Guillot
 */
class Logger implements LoggerAwareInterface
{
    /**
     * Logger instances
     *
     * @access private
     */
    private $loggers = array();

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->loggers[] = $logger;
    }

    /**
     * Proxy method to the real logger
     *
     * @access public
     * @param  string   $method     Method name
     * @param  array    $arguments  Method arguments
     */
    public function __call($method, array $arguments = array())
    {
        foreach ($this->loggers as $logger) {
            call_user_func_array(array($logger, $method), $arguments);
        }
    }
}
