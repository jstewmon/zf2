<?php

namespace Zend\Db\Sql;

use Zend\Db\Adapter\Adapter,
    Zend\Db\Adapter\PlatformInterface,
    Zend\Db\Adapter\Platform\Sql92,
    Zend\Db\Adapter\ParameterContainer;

class Update implements SqlInterface, ParameterizedSqlInterface
{
    const VALUES_MERGE = 'merge';
    const VALUES_SET   = 'set';

    protected $databaseOrSchema = null;
    protected $table = null;
    protected $emptyWhereProtection = true;
    protected $set = array();
    protected $where = null;

    public function __construct($table = null, $databaseOrSchema = null)
    {
        if ($table) {
            $this->table($table, $databaseOrSchema);
        }
    }

    public function table($table, $databaseOrSchema = null)
    {
        $this->table = $table;
        if ($databaseOrSchema) {
            $this->databaseOrSchema = $databaseOrSchema;
        }
        return $this;
    }

    public function set(array $values, $flag = self::VALUES_SET)
    {
        if ($values == null) {
            throw new \InvalidArgumentException('set() expects an array of values');
        }

        if ($flag == self::VALUES_SET) {
            $this->set = array();
        }

        foreach ($values as $k => $v) {
            if (!is_string($k)) {
                throw new \Exception('set() expects a string for the value key');
            }
            $this->set[$k] = $v;
        }

        return $this;
    }

    public function where($where)
    {
        //if (is_array($where)) {
        //    $whereArray = $where;
        //    $where = new Predicate\Predicate();
        //
        //}

        //if (!is_string($where) || !)
    }

    public function isValid($throwException = self::VALID_RETURN_BOOLEAN)
    {
        if ($this->table == null || !is_string($this->table)) {
            if ($throwException) throw new \Exception('A valid table name is required');
            return false;
        }

        if (count($this->values) == 0) {
            if ($throwException) throw new \Exception('Values are required for this insert object to be valid');
            return false;
        }

        if (count($this->columns) > 0 && count($this->columns) != count($this->values)) {
            if ($throwException) throw new \Exception('When columns are present, there needs to be an equal number of columns and values');
            return false;
        }

        return true;
    }

    public function getParameterizedSqlString(Adapter $adapter)
    {
        //$driver   = $this->db->adapter()->getDriver();
        //$platform = $this->db->adapter()->getPlatform();
        //
        //$table = $platform->quoteIdentifier($this->table);
        //$sql = 'INSERT INTO ' . $table . ' ';
        //
        //$columns = array_map(array($platform, 'quoteIdentifier'), $this->columns);
        //$sql .= '(' . implode(', ', $columns) . ') ';
        //
        //$type = $this->determineTypeFromDriver();
        //
        //switch ($type) {
        //    case self::TYPE_EXECUTABLE:
        //        $values = array_map(array($platform, 'quoteValue'), $this->values);
        //        $sql .= 'VALUES (' . implode(', ', $values) . ')';
        //        break;
        //    case self::TYPE_PREPARABLE_POSITIONAL:
        //        $sql .= 'VALUES (' . str_repeat('?,', count($columns)-1) . ' ?)';
        //        break;
        //    case self::TYPE_PREPARABLE_NAMED:
        //        $sql .= 'VALUES (';
        //        $sqlNames = array();
        //        foreach ($columns as $column) {
        //            $sqlNames = $driver->formatNamedParameter($column);
        //        }
        //        $sql .= implode(', ', $sqlNames) . ')';
        //        break;
        //}
        //
        //return $sql;
    }

    public function getParameterContainer()
    {
        //$type = $this->determineTypeFromDriver();
        //switch ($type) {
        //    case self::TYPE_EXECUTABLE:
        //    case self::TYPE_PREPARABLE_POSITIONAL:
        //        return $this->values;
        //    case self::TYPE_PREPARABLE_NAMED:
        //        $driver = $this->db->adapter()->getDriver();
        //        $values = array();
        //        foreach ($this->columns as $column) {
        //            $values[$driver->formatNamedParameter($column)] = $this->values[$column];
        //        }
        //        return $values;
        //}
    }

    public function getSqlString()
    {
        // TODO: Implement getSqlString() method.
    }

}
