<?php

namespace PicoDb;

use PDO;

class Table
{
    const SORT_ASC = 'ASC';
    const SORT_DESC = 'DESC';

    private $table_name = '';
    private $sql_limit = '';
    private $sql_offset = '';
    private $sql_order = '';
    private $joins = array();
    private $conditions = array();
    private $or_conditions = array();
    private $is_or_condition = false;
    private $columns = array();
    private $values = array();
    private $distinct = false;
    private $group_by = array();
    private $db;

    /**
     * Constructor
     *
     * @access public
     * @param  \PicoDb\Database   $db
     * @param  string             $table_name
     */
    public function __construct(Database $db, $table_name)
    {
        $this->db = $db;
        $this->table_name = $table_name;
    }

    /**
     * Insert or update
     *
     * @access public
     * @param  array    $data
     * @return boolean
     */
    public function save(array $data)
    {
        if (! empty($this->conditions)) {
            return $this->update($data);
        }
        else {
            return $this->insert($data);
        }
    }

    /**
     * Update
     *
     * Note: Do not use `rowCount()` for update the behaviour is different across drivers
     *
     * @access public
     * @param  array   $data
     * @return boolean
     */
    public function update(array $data)
    {
        $columns = array();
        $values = array();

        foreach ($data as $column => $value) {
            $columns[] = $this->db->escapeIdentifier($column).'=?';
            $values[] = $value;
        }

        foreach ($this->values as $value) {
            $values[] = $value;
        }

        $sql = sprintf(
            'UPDATE %s SET %s %s',
            $this->db->escapeIdentifier($this->table_name),
            implode(', ', $columns),
            $this->conditions()
        );

        return $this->db->execute($sql, $values) !== false;
    }

    /**
     * Insert
     *
     * @access public
     * @param  array    $data
     * @return boolean
     */
    public function insert(array $data)
    {
        $columns = array();

        foreach ($data as $column => $value) {
            $columns[] = $this->db->escapeIdentifier($column);
        }

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->db->escapeIdentifier($this->table_name),
            implode(', ', $columns),
            implode(', ', array_fill(0, count($data), '?'))
        );

        return $this->db->execute($sql, array_values($data)) !== false;
    }

    /**
     * Remove
     *
     * @access public
     * @return boolean
     */
    public function remove()
    {
        $sql = sprintf(
            'DELETE FROM %s %s',
            $this->db->escapeIdentifier($this->table_name),
            $this->conditions()
        );

        $result = $this->db->execute($sql, $this->values);
        return $result->rowCount() > 0;
    }

    /**
     * Hashmap result [ [column1 => column2], [], ...]
     *
     * @access public
     * @param  string    $key      Column 1
     * @param  string    $value    Column 2
     * @return array
     */
    public function listing($key, $value)
    {
        $listing = array();

        $this->columns($key, $value);
        $rq = $this->db->execute($this->buildSelectQuery(), $this->values);

        $rows = $rq->fetchAll(PDO::FETCH_NUM);

        foreach ($rows as $row) {
            $listing[$row[0]] = $row[1];
        }

        return $listing;
    }

    /**
     * Fetch all rows
     *
     * @access public
     * @return array
     */
    public function findAll()
    {
        $rq = $this->db->execute($this->buildSelectQuery(), $this->values);
        return $rq->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find all with a single column
     *
     * @access public
     * @param  string    $column
     * @return boolean
     */
    public function findAllByColumn($column)
    {
        $this->columns = array($column);
        $rq = $this->db->execute($this->buildSelectQuery(), $this->values);

        return $rq->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    /**
     * Fetch one row
     *
     * @access public
     * @param  array    $data
     * @return boolean
     */
    public function findOne()
    {
        $this->limit(1);
        $result = $this->findAll();

        return isset($result[0]) ? $result[0] : null;
    }

    /**
     * Fetch one column, first row
     *
     * @access public
     * @param  string   $column
     * @return string
     */
    public function findOneColumn($column)
    {
        $this->limit(1);
        $this->columns = array($column);

        $rq = $this->db->execute($this->buildSelectQuery(), $this->values);

        return $rq->fetchColumn();
    }

    /**
     * Build a select query
     *
     * @access public
     * @return string
     */
    public function buildSelectQuery()
    {
        foreach ($this->columns as $key => $value) {
            $this->columns[$key] = $this->db->escapeIdentifier($value);
        }

        return sprintf(
            'SELECT %s %s FROM %s %s %s %s %s %s %s',
            $this->distinct ? 'DISTINCT' : '',
            empty($this->columns) ? '*' : implode(', ', $this->columns),
            $this->db->escapeIdentifier($this->table_name),
            implode(' ', $this->joins),
            $this->conditions(),
            empty($this->group_by) ? '' : 'GROUP BY '.implode(', ', $this->group_by),
            $this->sql_order,
            $this->sql_limit,
            $this->sql_offset
        );
    }

    /**
     * Count
     *
     * @access public
     * @return integer
     */
    public function count()
    {
        $sql = sprintf(
            'SELECT COUNT(*) FROM %s'.$this->conditions().$this->sql_order.$this->sql_limit.$this->sql_offset,
            $this->db->escapeIdentifier($this->table_name)
        );

        $rq = $this->db->execute($sql, $this->values);

        $result = $rq->fetchColumn();
        return $result ? (int) $result : 0;
    }

    /**
     * Left join
     *
     * @access public
     * @param  string   $table              Join table
     * @param  string   $foreign_column     Foreign key on the join table
     * @param  string   $local_column       Local column
     * @param  string   $local_table        Local table
     * @return \PicoDb\Table
     */
    public function join($table, $foreign_column, $local_column, $local_table = '')
    {
        $this->joins[] = sprintf(
            'LEFT JOIN %s ON %s=%s',
            $this->db->escapeIdentifier($table),
            $this->db->escapeIdentifier($table).'.'.$this->db->escapeIdentifier($foreign_column),
            $this->db->escapeIdentifier($local_table ?: $this->table_name).'.'.$this->db->escapeIdentifier($local_column)
        );

        return $this;
    }

    /**
     * Build conditions
     *
     * @access public
     * @return string
     */
    public function conditions()
    {
        return empty($this->conditions) ? '' : ' WHERE '.implode(' AND ', $this->conditions);
    }

    /**
     * Add new condition
     *
     * @access public
     * @param  string   $sql
     */
    public function addCondition($sql)
    {
        if ($this->is_or_condition) {
            $this->or_conditions[] = $sql;
        }
        else {
            $this->conditions[] = $sql;
        }
    }

    /**
     * Start OR condition
     *
     * @access public
     * @return \PicoDb\Table
     */
    public function beginOr()
    {
        $this->is_or_condition = true;
        $this->or_conditions = array();
        return $this;
    }

    /**
     * Close OR condition
     *
     * @access public
     * @return \PicoDb\Table
     */
    public function closeOr()
    {
        $this->is_or_condition = false;

        if (! empty($this->or_conditions)) {
            $this->conditions[] = '('.implode(' OR ', $this->or_conditions).')';
        }

        return $this;
    }

    /**
     * Order by
     *
     * @access public
     * @param  string   $column    Column name
     * @param  string   $order     Direction ASC or DESC
     * @return \PicoDb\Table
     */
    public function orderBy($column, $order = self::SORT_ASC)
    {
        $order = strtoupper($order);
        $order = $order === self::SORT_ASC || $order === self::SORT_DESC ? $order : self::SORT_ASC;

        if ($this->sql_order === '') {
            $this->sql_order = ' ORDER BY '.$this->db->escapeIdentifier($column).' '.$order;
        }
        else {
            $this->sql_order .= ', '.$this->db->escapeIdentifier($column).' '.$order;
        }

        return $this;
    }

    /**
     * Ascending sort
     *
     * @access public
     * @param  string   $column
     * @return \PicoDb\Table
     */
    public function asc($column)
    {
        if ($this->sql_order === '') {
            $this->sql_order = ' ORDER BY '.$this->db->escapeIdentifier($column).' '.self::SORT_ASC;
        }
        else {
            $this->sql_order .= ', '.$this->db->escapeIdentifier($column).' '.self::SORT_ASC;
        }

        return $this;
    }

    /**
     * Descending sort
     *
     * @access public
     * @param  string   $column
     * @return \PicoDb\Table
     */
    public function desc($column)
    {
        if ($this->sql_order === '') {
            $this->sql_order = ' ORDER BY '.$this->db->escapeIdentifier($column).' '.self::SORT_DESC;
        }
        else {
            $this->sql_order .= ', '.$this->db->escapeIdentifier($column).' '.self::SORT_DESC;
        }

        return $this;
    }

    /**
     * Limit
     *
     * @access public
     * @param  integer   $value
     * @return \PicoDb\Table
     */
    public function limit($value)
    {
        if (! is_null($value)) {
            $this->sql_limit = ' LIMIT '.(int) $value;
        }

        return $this;
    }

    /**
     * Offset
     *
     * @access public
     * @param  integer   $value
     * @return \PicoDb\Table
     */
    public function offset($value)
    {
        if (! is_null($value)) {
            $this->sql_offset = ' OFFSET '.(int) $value;
        }

        return $this;
    }

    /**
     * Group by
     *
     * @access public
     * @return \PicoDb\Table
     */
    public function groupBy()
    {
        $this->group_by = func_get_args();
        return $this;
    }

    /**
     * Define the columns for the select
     *
     * @access public
     * @return \PicoDb\Table
     */
    public function columns()
    {
        $this->columns = func_get_args();
        return $this;
    }

    /**
     * Distinct
     *
     * @access public
     * @return \PicoDb\Table
     */
    public function distinct()
    {
        $this->columns = func_get_args();
        $this->distinct = true;
        return $this;
    }

    /**
     * Magic method for sql conditions
     *
     * @access public
     * @param  string   $name
     * @param  array    $arguments
     * @return \PicoDb\Table
     */
    public function __call($name, array $arguments)
    {
        $column = $arguments[0];
        $sql = '';

        switch (strtolower($name)) {

            case 'in':
                if (isset($arguments[1]) && is_array($arguments[1]) && ! empty($arguments[1])) {

                    $sql = sprintf(
                        '%s IN (%s)',
                        $this->db->escapeIdentifier($column),
                        implode(', ', array_fill(0, count($arguments[1]), '?'))
                    );
                }
                break;

            case 'notin':
                if (isset($arguments[1]) && is_array($arguments[1]) && ! empty($arguments[1])) {

                    $sql = sprintf(
                        '%s NOT IN (%s)',
                        $this->db->escapeIdentifier($column),
                        implode(', ', array_fill(0, count($arguments[1]), '?'))
                    );
                }
                break;

            case 'like':
                $sql = sprintf(
                    '%s %s ?',
                    $this->db->escapeIdentifier($column),
                    $this->db->getConnection()->operatorLikeCaseSensitive()
                );
                break;

            case 'ilike':
                $sql = sprintf(
                    '%s %s ?',
                    $this->db->escapeIdentifier($column),
                    $this->db->getConnection()->operatorLikeNotCaseSensitive()
                );
                break;

            case 'eq':
            case 'equal':
            case 'equals':
                $sql = sprintf('%s = ?', $this->db->escapeIdentifier($column));
                break;

            case 'neq':
            case 'notequal':
            case 'notequals':
                $sql = sprintf('%s != ?', $this->db->escapeIdentifier($column));
                break;

            case 'gt':
            case 'greaterthan':
                $sql = sprintf('%s > ?', $this->db->escapeIdentifier($column));
                break;

            case 'lt':
            case 'lowerthan':
                $sql = sprintf('%s < ?', $this->db->escapeIdentifier($column));
                break;

            case 'gte':
            case 'greaterthanorequals':
                $sql = sprintf('%s >= ?', $this->db->escapeIdentifier($column));
                break;

            case 'lte':
            case 'lowerthanorequals':
                $sql = sprintf('%s <= ?', $this->db->escapeIdentifier($column));
                break;

            case 'isnull':
                $sql = sprintf('%s IS NULL', $this->db->escapeIdentifier($column));
                break;

            case 'notnull':
                $sql = sprintf('%s IS NOT NULL', $this->db->escapeIdentifier($column));
                break;
        }

        if ($sql !== '') {

            $this->addCondition($sql);

            if (isset($arguments[1])) {

                if (is_array($arguments[1])) {

                    foreach ($arguments[1] as $value) {
                        $this->values[] = $value;
                    }
                }
                else {

                    $this->values[] = $arguments[1];
                }
            }
        }

        return $this;
    }
}
