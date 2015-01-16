<?php

require 'vendor/autoload.php';

// Setup Syslog logging
$logger = new SimpleLogger\Syslog('myapp');

// Output to syslog: "Jun  2 15:55:09 hostname myapp[2712]: boo"
$logger->error('boo');

// Output to syslog: "Jun  2 15:55:09 hostname myapp[2712]: Error at /Users/fred/Devel/libraries/simpleLogger/example.php at line 15"
$logger->error('Error at {filename} at line {line}', array('filename' => __FILE__, 'line' => __LINE__));

// Setup File logging
$logger = new SimpleLogger\File('/tmp/simplelogger.log');

// Output to the file: "[2013-06-02 16:03:28] [info] boo"
$logger->info('boo');

// Output to the file: "[2013-06-02 16:03:28] [error] Error at /Users/fred/Devel/libraries/simpleLogger/example.php at line 24"
$logger->error('Error at {filename} at line {line}', array('filename' => __FILE__, 'line' => __LINE__));

// Dump a variable
$values = array(
    'key' => 'value'
);

// Output: [2013-06-02 16:05:32] [debug] array (
//  'key' => 'value',
// )
$logger->dump($values);

// Multiple handlers
$logger = new SimpleLogger\Logger;
$logger->setLogger(new SimpleLogger\Syslog('myapp'));
$logger->setLogger(new SimpleLogger\File('/tmp/simplelogger.log'));
$logger->info('my message');
$logger->error('my error message');
$logger->error('my error message with a {variable}', array('variable' => 'test'));
