<?php

namespace PicoDb;

use PDO;

class Hashtable extends Table
{
    private $column_key = 'key';
    private $column_value = 'value';

    /**
     * Set the key column
     *
     * @access public
     * @param  string $column
     * @return \PicoDb\Table
     */
    public function columnKey($column)
    {
        $this->column_key = $column;
        return $this;
    }

    /**
     * Set the value column
     *
     * @access public
     * @param  string $column
     * @return \PicoDb\Table
     */
    public function columnValue($column)
    {
        $this->column_value = $column;
        return $this;
    }

    /**
     * Insert or update
     *
     * @access public
     * @param  array    $data
     * @return boolean
     */
    public function put(array $data)
    {
        switch ($this->db->getConnection()->getAttribute(PDO::ATTR_DRIVER_NAME)) {
            case 'mysql':
                $sql = sprintf(
                    'REPLACE INTO %s (%s) VALUES %s',
                    $this->db->escapeIdentifier($this->table_name),
                    "$this->column_key, $this->column_value",
                    implode(', ', array_fill(0, count($data), '(?, ?)'))
                );

                foreach ($data as $key => $value) {
                    $this->values[] = $key;
                    $this->values[] = $value;
                }

                $this->db->execute($sql, $this->values);
                break;
            case 'sqlite': // multi insert (see mysql) requires sqlite library > 3.7.11 (bundled with PHP 5.5.11+)
                // all or nothing
                $this->db->startTransaction();

                foreach($data as $key => $value) {
                    $sql = sprintf(
                        'INSERT OR REPLACE INTO %s (%s) VALUES (?, ?)',
                        $this->db->escapeIdentifier($this->table_name),
                        $this->db->escapeIdentifier($this->column_key).', '.$this->db->escapeIdentifier($this->column_value)
                    );

                    $this->db->execute($sql, array($key, $value));
                }

                break;
            default: // no upsert available, need to make a select/update/insert limbo
                // all or nothing
                $this->db->startTransaction();

                foreach($data as $key => $value) {
                    // check if the key exists
                    $this->eq($this->column_key, $key);

                    if ($this->count() === 1) {
                        // update the record
                        $this->update(array($this->column_key => $key, $this->column_value => $value));
                    }
                    else {
                        // insert the record
                        $this->insert(array($this->column_key => $key, $this->column_value => $value));
                    }
                }
        }

        $this->db->closeTransaction();

        return true;
    }

    /**
     * Hashmap result [ [column1 => column2], [], ...]
     *
     * @access public
     * @return array
     */
    public function get()
    {
        $hashmap = array();

        // setup where condition
        if (func_num_args() > 0) {
            $this->in($this->column_key, func_get_args());
        }

        // setup to select columns in case that there are more than two
        $this->columns($this->column_key, $this->column_value);

        $rq = $this->db->execute($this->buildSelectQuery(), $this->values);
        $rows = $rq->fetchAll(PDO::FETCH_NUM);

        foreach ($rows as $row) {
            $hashmap[$row[0]] = $row[1];
        }

        return $hashmap;
    }

    /**
     * Shortcut method to get a hashmap result
     *
     * @access public
     * @param  string  $key    Key column
     * @param  string  $value  Value column
     * @return array
     */
    public function getAll($key, $value)
    {
        $this->column_key = $key;
        $this->column_value = $value;
        return $this->get();
    }
}
