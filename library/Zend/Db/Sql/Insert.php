<?php

namespace Zend\Db\Sql;

use Zend\Db\Adapter\Adapter,
    Zend\Db\Adapter\PlatformInterface,
    Zend\Db\Adapter\Platform\Sql92;

class Insert implements ParameterizedSqlInterface, SqlInterface
{
    const VALUES_MERGE = 'merge';
    const VALUES_SET   = 'set';

    protected $databaseOrSchema = null;
    protected $table = null;
    protected $columns = array();
    protected $values = array();

    public function __construct($table = null, $databaseOrSchema = null)
    {
        if ($table) {

        }
    }

    public function into($table, $databaseOrSchema = null)
    {
        $this->table = $table;
        if ($databaseOrSchema) {
            $this->databaseOrSchema = $databaseOrSchema;
        }
        return $this;
    }

    public function columns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function values(array $values, $flag = self::VALUES_SET)
    {
        if ($values == null) {
            throw new \InvalidArgumentException('values() expects an array of values');
        }

        $keys = array_keys($values);
        $firstKey = current($keys);

        if (is_string($firstKey)) {
            $this->columns($keys);
            $values = array_values($values);
        } elseif (is_int($firstKey)) {
            $values = array_values($values);
        }

        if ($flag == self::VALUES_MERGE) {
            $this->values = array_merge($this->values, $values);
        } else {
            $this->values = $values;
        }

        return $this;
    }

    public function __set($name, $value)
    {
        $values = array($name => $value);
        $this->values($values, self::VALUES_MERGE);
        return $this;
    }

    public function __unset($name)
    {
        if (!($position = array_search($name, $this->columns))) {
            throw new \InvalidArgumentException('Not in statement');
        }

        unset($this->columns[$position]);
        unset($this->values[$name]);
    }

    public function __isset($name)
    {
        return in_array($name, $this->columns);
    }

    public function __get($name)
    {
        if (!($position = array_search($name, $this->columns))) {
            throw new \InvalidArgumentException('Not in statement');
        }
        return $this->values[$name];
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

    public function getSqlString(PlatformInterface $platform = null)
    {
        $platform = ($platform) ?: new Sql92;

        $table = $platform->quoteIdentifier($this->table);

        if ($this->databaseOrSchema != '') {
            $table = $platform->quoteIdentifier($this->databaseOrSchema) . $platform->getIdentifierSeparator() . $table;
        }

        $sql = 'INSERT INTO ' . $table . ' ';

        $columns = array_map(array($platform, 'quoteIdentifier'), $this->columns);
        $sql .= '(' . implode(', ', $columns) . ') ';

        $values = array_map(array($platform, 'quoteValue'), $this->values);
        $sql .= 'VALUES (' . implode(', ', $values) . ')';

        return $sql;
    }

    public function toPreparedStatement(\Zend\Db\Adapter\Adapter $adapter, $prepareType = null, $bindInitialValues = false)
    {
        $driver = $adapter->getDriver();
        $platform = $adapter->getPlatform();

        $table = $platform->quoteIdentifier($this->table);

        if ($this->databaseOrSchema != '') {
            $table = $platform->quoteIdentifier($this->schema) . '.' . $table;
        }

        $sql = 'INSERT INTO ' . $table . ' ';

        $columns = array_map(array($platform, 'quoteIdentifier'), $this->columns);
        $sql .= '(' . implode(', ', $columns) . ') ';

        switch ($prepareType) {
            case \Zend\Db\Adapter\Adapter::PREPARE_TYPE_NAMED:
                $sql .= 'VALUES (';
                $sqlNames = array();
                foreach ($columns as $column) {
                    $sqlNames = $driver->formatNamedParameter($column);
                }
                $sql .= implode(', ', $sqlNames) . ')';
                $statement = $adapter->query($sql, $adapter::QUERY_MODE_PREPARE);
                break;
            case \Zend\Db\Adapter\Adapter::PREPARE_TYPE_POSITIONAL:
            default:
                $sql .= 'VALUES (' . str_repeat('?,', count($columns)-1) . ' ?)';
                break;
        }

        return $sql;
    }

    public function getValues($type = null)
    {
        switch ($type) {
            case self::TYPE_PREPARABLE_NAMED:
                $driver = $this->db->adapter()->getDriver();
                $values = array();
                foreach ($this->columns as $column) {
                    $values[$driver->formatNamedParameter($column)] = $this->values[$column];
                }
                return $values;
            case self::TYPE_PREPARABLE_POSITIONAL:
            default:
                return $this->values;
        }
    }

}