<?php

namespace Zend\Db\Adapter;

interface DriverConnection
{
    public function __construct(Driver\AbstractDriver $driver, array $connectionParameters);
    public function getConnectionParams();
    public function getDefaultCatalog();
    public function getDefaultSchema();
    public function getResource();
    public function connect();
    public function isConnected();
    public function disconnect();
    public function beginTransaction();
    public function commit();
    public function rollback();
    public function execute($sql); // return result set
    
    /**
     * @return Zend\Db\Adapter\DriverStatement
     */
    public function prepare($sql); // must return StatementInterface object
}
