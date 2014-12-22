PicoDb
======

PicoDb is a minimalist database query builder for PHP
**It's not an ORM**.

Features
--------

- No dependency
- Easy to use, fast and very lightweight
- Use prepared statements
- Handle schema versions (migrations)
- License: [WTFPL](http://www.wtfpl.net)

Requirements
------------

- PHP >= 5.3
- PDO
- A database: Sqlite, Mysql or Postgresql

Todo
----

- Add driver for Postgresql
- Add support for Distinct...

Documentation
-------------

## Connect to your database

    use PicoDb\Database;

    // Sqlite driver
    $db = new Database(['driver' => 'sqlite', 'filename' => ':memory:']);

    // Mysql driver
    // Optional options: "schema_table" (the default table name is "schema_version")
    $db = new Database(array(
        'driver' => 'mysql',
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'my_db_name',
        'charset' => 'utf8',
    ));

## Execute a SQL request

    $db->execute('CREATE TABLE toto (column1 TEXT)');

## Insert some data

    $db->table('toto')->save(['column1' => 'hey']);

## Transations

    $db->transaction(function($db) {
        $db->table('toto')->save(['column1' => 'foo']);
        $db->table('toto')->save(['column1' => 'bar']);
    });

## Fetch all data

    $records = $db->table('toto')->findAll();

    foreach ($records as $record) {
        var_dump($record['column1']);
    }

## Update something

    $db->table('toto')->eq('id', 1)->save(['column1' => 'hey']);

You just need to add a condition to perform an update.

## Remove rows

    $db->table('toto')->lowerThan('column1', 10)->remove();

## Sorting

    $db->table('toto')->asc('column1')->findAll();

or

    $db->table('toto')->desc('column1')->findAll();

## Limit and offset

    $db->table('toto')->limit(10)->offset(5)->findAll();

## Fetch only some columns

    $db->table('toto')->columns('column1', 'column2')->findAll();

## Conditions

### Equals condition

    $db->table('toto')
       ->equals('column1', 'hey')
       ->findAll();

or

    $db->table('toto')
       ->eq('column1', 'hey')
       ->findAll();

Yout got: 'SELECT * FROM toto WHERE column1=?'

### IN condition

    $db->table('toto')
           ->in('column1', ['hey', 'bla'])
           ->findAll();

### Like condition

    $db->table('toto')
       ->like('column1', '%hey%')
       ->findAll();

### Lower than

    $db->table('toto')
       ->lowerThan('column1', 2)
       ->findAll();

or

    $db->table('toto')
       ->lt('column1', 2)
       ->findAll();

### Lower than or equals

    $db->table('toto')
       ->lowerThanOrEquals('column1', 2)
       ->findAll();

or

    $db->table('toto')
       ->lte('column1', 2)
       ->findAll();

### Greater than

    $db->table('toto')
       ->greaterThan('column1', 3)
       ->findAll();

or

    $db->table('toto')
       ->gt('column1', 3)
       ->findAll();

### Greater than or equals

    $db->table('toto')
       ->greaterThanOrEquals('column1', 3)
       ->findAll();

or

    $db->table('toto')
        ->gte('column1', 3)
        ->findAll();

### Multiple conditions

Each condition is joined by a AND.

    $db->table('toto')
        ->like('column2', '%toto')
        ->gte('column1', 3)
        ->findAll();

How to make a OR condition:

    $db->table('toto')
        ->beginOr()
        ->like('column2', '%toto')
        ->gte('column1', 3)
        ->closeOr()
        ->eq('column5', 'titi')
        ->findAll();

## Schema migrations

### Define a migration

- Migrations are defined in simple functions inside a namespace named "Schema".
- An instance of PDO is passed to first argument of the function.
- Function names has the version number at the end.

Example:

    namespace Schema;

    function version_1($pdo)
    {
        $pdo->exec('
            CREATE TABLE users (
                id INTEGER PRIMARY KEY,
                name TEXT UNIQUE,
                email TEXT UNIQUE,
                password TEXT
            )
        ');
    }


    function version_2($pdo)
    {
        $pdo->exec('
            CREATE TABLE tags (
                id INTEGER PRIMARY KEY,
                name TEXT UNIQUE
            )
        ');
    }

### Run schema update automatically

- The method "check()" executes all migrations until to reach the correct version number.
- If we are already on the last version nothing will happen.
- The schema version for the driver Sqlite is stored inside a variable (PRAGMA user_version)
- You can use that with a dependency injection controller.

Example:

    $last_schema_version = 5;

    $db = new PicoDb\Database(array(
        'driver' => 'sqlite',
        'filename' => '/tmp/mydb.sqlite'
    ));

    if ($db->schema()->check($last_schema_version)) {

        // Do something...
    }
    else {

        die('Unable to migrate database schema.');
    }

### Use a singleton to handle database instances

Setup a new instance:

    PicoDb\Database::bootstrap('myinstance', function() {

        $db = new PicoDb\Database(array(
            'driver' => 'sqlite',
            'filename' => DB_FILENAME
        ));

        if ($db->schema()->check(DB_VERSION)) {
            return $db;
        }
        else {
            die('Unable to migrate database schema.');
        }
    });

Get this instance anywhere in your code:

    PicoDb\Database::get('myinstance')->table(...)
