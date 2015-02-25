<?php

namespace PicoDb;

use Closure;
use PDO;
use PDOException;
use LogicException;
use RuntimeException;
use Picodb\Driver\Sqlite;
use Picodb\Driver\Mysql;
use Picodb\Driver\Postgres;

class Database
{
    /**
     * Database instances
     *
     * @access private
     * @static
     * @var array
     */
    private static $instances = array();

    /**
     * Queries logs
     *
     * @access private
     * @var array
     */
    private $logs = array();

    /**
     * PDO instance
     *
     * @access private
     * @var PDO
     */
    private $pdo;

    /**
     * Flag to calculate query time
     *
     * @access public
     * @var boolean
     */
    public $stopwatch = false;

    /**
     * Flag to log generated SQL queries
     *
     * @access public
     * @var boolean
     */
    public $log_queries = false;

    /**
     * Number of SQL queries executed
     *
     * @access public
     * @var integer
     */
    public $nb_queries = 0;

    /**
     * Constructor, iniatlize a PDO driver
     *
     * @access public
     * @param  array     $settings    Connection settings
     */
    public function __construct(array $settings)
    {
        if (! isset($settings['driver'])) {
            throw new LogicException('You must define a database driver.');
        }

        switch ($settings['driver']) {

            case 'sqlite':
                require_once __DIR__.'/Driver/Sqlite.php';
                $this->pdo = new Sqlite($settings);
                break;

            case 'mysql':
                require_once __DIR__.'/Driver/Mysql.php';
                $this->pdo = new Mysql($settings);
                break;

            case 'postgres':
                require_once __DIR__.'/Driver/Postgres.php';
                $this->pdo = new Postgres($settings);
                break;

            default:
                throw new LogicException('This database driver is not supported.');
        }

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Destructor
     *
     * @access public
     */
    public function __destruct()
    {
        $this->closeConnection();
    }

    /**
     * Register a new database instance
     *
     * @static
     * @access public
     * @param  string    $name        Instance name
     * @param  Closure   $callback    Callback
     */
    public static function bootstrap($name, Closure $callback)
    {
        self::$instances[$name] = $callback;
    }

    /**
     * Get a database instance
     *
     * @static
     * @access public
     * @param  string    $name   Instance name
     * @return Database
     */
    public static function get($name)
    {
        if (! isset(self::$instances[$name])) {
            throw new LogicException('No database instance created with that name.');
        }

        if (is_callable(self::$instances[$name])) {
            self::$instances[$name] = call_user_func(self::$instances[$name]);
        }

        return self::$instances[$name];
    }

    /**
     * Add a log message
     *
     * @access public
     * @param  string    $message   Message
     */
    public function setLogMessage($message)
    {
        $this->logs[] = $message;
    }

    /**
     * Get all queries logs
     *
     * @access public
     * @return array
     */
    public function getLogMessages()
    {
        return $this->logs;
    }

    /**
     * Get the PDO connection
     *
     * @access public
     * @return PDO
     */
    public function getConnection()
    {
        return $this->pdo;
    }

    /**
     * Release the PDO connection
     *
     * @access public
     */
    public function closeConnection()
    {
        $this->pdo = null;
    }

    /**
     * Escape an identifier (column, table name...)
     *
     * @access public
     * @param  string    $value    Value
     * @param  string    $table    Table name
     * @return string
     */
    public function escapeIdentifier($value, $table = '')
    {
        // Do not escape custom query
        if (strpos($value, '.') !== false || strpos($value, ' ') !== false) {
            return $value;
        }

        if (! empty($table)) {
            return $this->pdo->escapeIdentifier($table).'.'.$this->pdo->escapeIdentifier($value);
        }

        return $this->pdo->escapeIdentifier($value);
    }

    /**
     * Execute a prepared statement
     *
     * @access public
     * @param  string    $sql      SQL query
     * @param  array     $values   Values
     * @return PDOStatement|false
     */
    public function execute($sql, array $values = array())
    {
        try {

            if ($this->log_queries) {
                $this->setLogMessage($sql);
            }

            if ($this->stopwatch) {
                $start = microtime(true);
            }

            $rq = $this->pdo->prepare($sql);
            $rq->execute($values);

            if ($this->stopwatch) {
                $this->setLogMessage('DURATION='.(microtime(true) - $start));
            }

            $this->nb_queries++;

            return $rq;
        }
        catch (PDOException $e) {

            $this->cancelTransaction();

            if (in_array($e->getCode(), $this->pdo->getDuplicateKeyErrorCode())) {
                return false;
            }

            $this->setLogMessage($e->getMessage());

            throw new RuntimeException('SQL error');
        }
    }

    /**
     * Run a transaction
     *
     * @access public
     * @param  Closure    $callback     Callback
     * @return mixed
     */
    public function transaction(Closure $callback)
    {
        $this->pdo->beginTransaction();
        $result = $callback($this); // Rollback is done in the execute() method
        $this->closeTransaction();

        return $result === null ? true : $result;
    }

    /**
     * Begin a transaction
     *
     * @access public
     */
    public function startTransaction()
    {
        if (! $this->pdo->inTransaction()) {
            $this->pdo->beginTransaction();
        }
    }

    /**
     * Commit a transaction
     *
     * @access public
     */
    public function closeTransaction()
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->commit();
        }
    }

    /**
     * Rollback a transaction
     *
     * @access public
     */
    public function cancelTransaction()
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollback();
        }
    }

    /**
     * Get a table instance
     *
     * @access public
     * @return Picodb\Table
     */
    public function table($table_name)
    {
        require_once __DIR__.'/Table.php';
        return new Table($this, $table_name);
    }

    /**
     * Get a hashtable instance
     *
     * @access public
     * @return Picodb\Hashtable
     */
    public function hashtable($table_name)
    {
        require_once __DIR__.'/Table.php';
        require_once __DIR__.'/Hashtable.php';
        return new Hashtable($this, $table_name);
    }

    /**
     * Get a schema instance
     *
     * @access public
     * @return Picodb\Schema
     */
    public function schema()
    {
        require_once __DIR__.'/Schema.php';
        return new Schema($this);
    }
}