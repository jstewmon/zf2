<?php

namespace Zend\Db\Metadata;

interface MetadataInterface
{
    // public function getDatabases();
    // public function getSchemas($database = null);
    public function getTables(); //$schema = null, $database = null);
    public function getViews(); //$schema = null, $database = null);
    public function getTriggers(); //$schema = null, $database = null);
    public function getReferentialConstraints(); //$schema = null, $database = null);
    public function getConstraints(); //$schema = null, $database = null);
    public function getColumns($table); //, $schema = null, $database = null);
    public function getConstraintKeys($constraint, $table); //, $schema = null, $database = null);
}