<?php

namespace PicoDb;

use PDOException;

/**
 * Schema migration class
 *
 * @author   Frederic Guillot
 */
class Schema
{
    /**
     * Database instance
     *
     * @access protected
     * @var Database
     */
    protected $db = null;

    /**
     * Constructor
     *
     * @access public
     * @param  Database  $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Check the schema version and run the migrations
     *
     * @access public
     * @param  integer  $last_version
     * @return boolean
     */
    public function check($last_version = 1)
    {
        $current_version = $this->db->getDriver()->getSchemaVersion();

        if ($current_version < $last_version) {
            return $this->migrateTo($current_version, $last_version);
        }

        return true;
    }

    /**
     * Migrate the schema to one version to another
     *
     * @access public
     * @param  integer  $current_version
     * @param  integer  $next_version
     * @return boolean
     */
    public function migrateTo($current_version, $next_version)
    {
        try {
            for ($i = $current_version + 1; $i <= $next_version; $i++) {
                $this->db->startTransaction();
                $this->db->getDriver()->disableForeignKeys();

                $function_name = '\Schema\version_'.$i;

                if (function_exists($function_name)) {
                    $this->db->setLogMessage('Running migration '.$function_name);
                    call_user_func($function_name, $this->db->getConnection());
                }

                $this->db->getDriver()->setSchemaVersion($i);
                $this->db->getDriver()->enableForeignKeys();
                $this->db->closeTransaction();
            }
        } catch (PDOException $e) {
            $this->db->setLogMessage($e->getMessage());
            $this->db->cancelTransaction();
            $this->db->getDriver()->enableForeignKeys();
            return false;
        }

        return true;
    }
}
