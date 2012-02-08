<?php

namespace Zend\Db\Sql;

use Zend\Db\Adapter\Adapter;

class Select implements SqlInterface, ParameterizedSqlInterface
{

    protected $table = null;
    protected $databaseOrSchema = null;
    protected $columns = null;
    protected $where = null;

    public function __construct($table = null, $databaseOrSchema = null)
    {
        if ($table) {
            $this->from($table, $databaseOrSchema);
        }
    }

    public function from($table, $databaseOrSchema = null)
    {
    }

    public function columns($cols = '*', $correlationName = null)
    {
    }

    public function union($select = array(), $type = self::SQL_UNION)
    {
    }

    public function join($name, $cond, $cols = self::SQL_WILDCARD, $schema = null, $type = null)
    {
    }

    public function where($cond, $value = null, $type = null)
    {
    }

    public function getParameterizedSqlString(Adapter $adapter)
    {
        // TODO: Implement getParameterizedSqlString() method.
    }

    public function getParameterContainer()
    {
        // TODO: Implement getParameterContainer() method.
    }

    public function getSqlString()
    {
        // TODO: Implement getSqlString() method.
    }
}