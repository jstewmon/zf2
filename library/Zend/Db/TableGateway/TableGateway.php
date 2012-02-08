<?php

namespace Zend\Db\TableGateway;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\ResultSet\ResultSetInterface;

class TableGateway implements TableGatewayInterface
{
    const USE_STATIC_ADAPTER = null;

    /**
     * @var \Zend\Db\Adapter\Adapter[]
     */
    protected static $staticAdapters = array();

    /**
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $adapter = null;

    /**
     * @var string
     */
    protected $tableName = null;

    /**
     * @var null|string
     */
    protected $databaseSchema = null;

    /**
     * @var null
     */
    protected $selectResultPrototype = null;

    public static function setStaticAdapter(Adapter $adapter)
    {
        $class = get_called_class();

        static::$staticAdapters[$class] = $adapter;
        if ($class === __CLASS__) {
            static::$staticAdapters[__CLASS__] = $adapter;
        }
    }

    public static function getStaticAdapter()
    {
        $class = get_called_class();

        // class specific adapter
        if (isset(static::$staticAdapters[$class])) {
            return static::$staticAdapters[$class];
        }

        // default adapter
        if (isset(static::$staticAdapters[__CLASS__])) {
            return static::$staticAdapters[__CLASS__];
        }

        throw new \Exception('No database adapter was found.');
    }

    public function __construct($tableName, Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        $this->setTableName($tableName);

        if ($adapter === self::USE_STATIC_ADAPTER) {
            $adapter = static::getStaticAdapter();
        }

        $this->setAdapter($adapter);

        if (is_string($databaseSchema)) {
            $this->databaseSchema = $databaseSchema;
        }
        $this->setSelectResultPrototype(($selectResultPrototype) ?: new ResultSet);
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }


    /**
     * @param null $selectResultPrototype
     */
    public function setSelectResultPrototype($selectResultPrototype)
    {
        $this->selectResultPrototype = $selectResultPrototype;
    }

    public function getSelectResultPrototype()
    {
        return $this->selectResultPrototype;
    }

    public function select($where)
    {
        // replace with Db\Sql select
        $adapter  = $this->adapter;
        $driver   = $adapter->getDriver();
        $platform = $adapter->getPlatform();

        $sql = 'SELECT * FROM ';
        if ($this->databaseSchema != '') {
            $sql .= $platform->quoteIdentifier($this->databaseSchema)
                . $platform->getIdentifierSeparator();
        }

        $whereSql = $where;
        if (is_array($where)) {
            $whereSql = $parameters = array();
            foreach ($where as $whereName => $whereValue) {
                $whereParamName = $driver->formatParameterName($whereName);
                $whereSql[] = $platform->quoteIdentifier($whereName) . ' = ' . $whereParamName;
                $whereParameters[$whereParamName] = $whereValue;
            }
            $whereSql = implode(' AND ', $whereSql);
        }
        $sql .= $platform->quoteIdentifier($this->tableName)
            . ' WHERE ' . $whereSql;

        $statement = $driver->getConnection()->prepare($sql);
        $result = $statement->execute($whereParameters);

        // return result set
        $resultSet = clone $this->selectResultPrototype;
        $resultSet->setDataSource($result);
        return $resultSet;
    }

    public function insert($set)
    {
        // replace with Db\Sql select
        $adapter  = $this->adapter;
        $driver   = $adapter->getDriver();
        $platform = $adapter->getPlatform();

        $sql = 'INSERT INTO ';
        if ($this->databaseSchema != '') {
            $sql .= $platform->quoteIdentifier($this->databaseSchema)
                . $platform->getIdentifierSeparator();
        }
        $sql .= $platform->quoteIdentifier($this->tableName);

        $setSql = $set;
        if (is_array($set)) {
            $setSqlColumns = $setSqlValues = $parameters = array();
            foreach ($set as $setName => $setValue) {
                $setParamName = $driver->formatParameterName($setName);
                $setSqlColumns[] = $platform->quoteIdentifier($setName);
                $setSqlValues[]  = $setParamName;
                $setParameters[$setName] = $setValue;
            }
            $setSql = '(' . implode(', ', $setSqlColumns) . ') VALUES (' . implode(', ', $setSqlValues) . ')';
        }
        $sql .= ' ' . $setSql;

        $statement = $driver->getConnection()->prepare($sql);
        $result = $statement->execute($setParameters);

        // return affected rows
        return $result->getAffectedRows();
    }

    public function update($set, $where)
    {
        // replace with Db\Sql select
        $adapter  = $this->adapter;
        $driver   = $adapter->getDriver();
        $platform = $adapter->getPlatform();

        $sql = 'UPDATE ';
        if ($this->databaseSchema != '') {
            $sql .= $platform->quoteIdentifier($this->databaseSchema)
                . $platform->getIdentifierSeparator();
        }
        $sql .= $platform->quoteIdentifier($this->tableName);

        $parameters = array();

        $setSql = $set;
        if (is_array($where)) {
            $setSql = array();
            foreach ($set as $setName => $setValue) {
                $setParamName = $driver->formatParameterName($setName);
                $setSql[] = $platform->quoteIdentifier($setName) . ' = ' . $setParamName;
                $parameters[$setParamName] = $setValue;
            }
            $setSql = implode(', ', $setSql);
        }
        $sql .= ' SET ' . $setSql;

        $whereSql = $where;
        if (is_array($where)) {
            $whereSql = array();
            foreach ($where as $whereName => $whereValue) {
                $whereParamName = $driver->formatParameterName($whereName);
                $whereSql[] = $platform->quoteIdentifier($whereName) . ' = ' . $whereParamName;
                $parameters[$whereName] = $whereValue;
            }
            $whereSql = implode(' AND ', $whereSql);
        }
        $sql .= ' WHERE ' . $whereSql;

        $statement = $driver->getConnection()->prepare($sql);
        $result = $statement->execute($parameters);

        // return affected rows
        return $result->getAffectedRows();
    }

    public function delete($where)
    {
        // replace with Db\Sql select
        $adapter  = $this->adapter;
        $driver   = $adapter->getDriver();
        $platform = $adapter->getPlatform();

        $sql = 'DELETE FROM ';
        if ($this->databaseSchema != '') {
            $sql .= $platform->quoteIdentifier($this->databaseSchema)
                . $platform->getIdentifierSeparator();
        }

        $whereSql = $where;
        if (is_array($where)) {
            $whereSql = $parameters = array();
            foreach ($where as $whereName => $whereValue) {
                $whereParamName = $driver->formatParameterName($whereName);
                $whereSql[] = $platform->quoteIdentifier($whereName) . ' = ' . $whereParamName;
                $whereParameters[$whereName] = $whereValue;
            }
            $whereSql = implode(' AND ', $whereSql);
        }
        $sql .= $platform->quoteIdentifier($this->tableName)
            . ' WHERE ' . $whereSql;

        $statement = $driver->getConnection()->prepare($sql);
        $result = $statement->execute($whereParameters);

        // return affected rows
        return $result->getAffectedRows();
    }


}
