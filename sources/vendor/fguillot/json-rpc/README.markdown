JsonRPC PHP Client and Server
=============================

A simple Json-RPC client/server that just works.

Features
--------

- JSON-RPC 2.0 protocol only
- The server support batch requests and notifications
- Authentication and IP based client restrictions
- Minimalist: there is only 2 files
- Fully unit tested
- License: Unlicense http://unlicense.org/

Requirements
------------

- The only dependency is the cURL extension
- PHP >= 5.3

Author
------

[Frédéric Guillot](http://fredericguillot.com)

Installation with Composer
--------------------------

```bash
composer require fguillot/json-rpc dev-master
```

Examples
--------

### Server

Callback binding:

```php
<?php

use JsonRPC\Server;

$server = new Server;

// Procedures registration

$server->register('addition', function ($a, $b) {
    return $a + $b;
});

$server->register('random', function ($start, $end) {
    return mt_rand($start, $end);
});

// Return the response to the client
echo $server->execute();
```

Class/Method binding:

```php
<?php

use JsonRPC\Server;

class Api
{
    public function doSomething($arg1, $arg2 = 3)
    {
        return $arg1 + $arg2;
    }
}

$server = new Server;

// Bind the method Api::doSomething() to the procedure myProcedure
$server->bind('myProcedure', 'Api', 'doSomething');

// Use a class instance instead of the class name
$server->bind('mySecondProcedure', new Api, 'doSomething');

echo $server->execute();
```

### Client

Example with positional parameters:

```php
<?php

use JsonRPC\Client;

$client = new Client('http://localhost/server.php');
$result = $client->execute('addition', [3, 5]);

var_dump($result);
```

Example with named arguments:

```php
<?php

require 'JsonRPC/Client.php';

use JsonRPC\Client;

$client = new Client('http://localhost/server.php');
$result = $client->execute('random', ['end' => 10, 'start' => 1]);

var_dump($result);
```

Arguments are called in the right order.

Examples with shortcut methods:

```php
<?php

use JsonRPC\Client;

$client = new Client('http://localhost/server.php');
$result = $client->random(50, 100);

var_dump($result);
```

The example above use positional arguments for the request and this one use named arguments:

```php
$result = $client->random(['end' => 10, 'start' => 1]);
```

### Client batch requests

Call several procedures in a single HTTP request:

```php
<?php

use JsonRPC\Client;

$client = new Client('http://localhost/server.php');

$results = $client->batch();
                  ->foo(['arg1' => 'bar'])
                  ->random(1, 100);
                  ->add(4, 3);
                  ->execute('add', [2, 5])
                  ->send();

print_r($results);
```

All results are stored at the same position of the call.

### Client exceptions

- `BadFunctionCallException`: Procedure not found on the server
- `InvalidArgumentException`: Wrong procedure arguments
- `RuntimeException`: Protocol error

### Enable client debugging

You can enable the debug to see the JSON request and response:

```php
<?php

use JsonRPC\Client;

$client = new Client('http://localhost/server.php');
$client->debug = true;
```

The debug output is sent to the PHP's system logger.
You can configure the log destination in your `php.ini`.

Output example:

```json
==> Request:
{
    "jsonrpc": "2.0",
    "method": "removeCategory",
    "id": 486782327,
    "params": [
        1
    ]
}
==> Response:
{
    "jsonrpc": "2.0",
    "id": 486782327,
    "result": true
}
```

### IP based client restrictions

The server can allow only some IP adresses:

```php
<?php

use JsonRPC\Server;

$server = new Server;

// IP client restrictions
$server->allowHosts(['192.168.0.1', '127.0.0.1']);

// Procedures registration

[...]

// Return the response to the client
echo $server->execute();
```

If the client is blocked, you got a 403 Forbidden HTTP response.

### HTTP Basic Authentication

If you use HTTPS, you can allow client by using a username/password.

```php
<?php

use JsonRPC\Server;

$server = new Server;

// List of users to allow
$server->authentication(['jsonrpc' => 'toto']);

// Procedures registration

[...]

// Return the response to the client
echo $server->execute();
```

On the client, set credentials like that:

```php
<?php

use JsonRPC\Client;

$client = new Client('http://localhost/server.php');
$client->authentication('jsonrpc', 'toto');
```

If the authentication failed, the client throw a RuntimeException.
