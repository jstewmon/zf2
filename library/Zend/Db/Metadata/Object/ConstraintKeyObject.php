<?php

namespace Zend\Db\Metadata\Object;

class ConstraintKey
{
    /*
    protected $catalogName = null;
    protected $schemaName = null;
    */

    protected $tableCatalogName = null;
    protected $tableSchemaName = null;
    protected $tableName = null;
    
    protected $columnName = null;
    protected $ordinalPosition = null;
    protected $positionInUniqueConstraint = null;
    protected $referencedTableSchema = null;
    protected $referencedTableName = null;
    protected $referencedColumnName = null;
    
    /*
    public function getCatalogName()
    {
        return $this->catalogName;
    }
    
    public function setCatalogName($catalogName)
    {
        $this->catalogName = $catalogName;
        return $this;
    }
    
    public function getSchemaName()
    {
        return $this->schemaName;
    }
    
    public function setSchemaName($schemaName)
    {
        $this->schemaName = $schemaName;
        return $this;
    }
    */
    
    public function getTableCatalogName()
    {
        return $this->tableCatalogName;
    }
    
    public function setTableCatalogName($tableCatalogName)
    {
        $this->tableCatalogName = $tableCatalogName;
        return $this;
    }
    
    public function getTableSchemaName()
    {
        return $this->tableSchemaName;
    }
    
    public function setTableSchemaName($tableSchemaName)
    {
        $this->tableSchemaName = $tableSchemaName;
        return $this;
    }
    
    public function getTableName()
    {
        return $this->tableName;
    }
    
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }
    
    public function getColumnName()
    {
        return $this->columnName;
    }
    
    public function setColumnName($columnName)
    {
        $this->columnName = $columnName;
        return $this;
    }
    
    public function getOrdinalPosition()
    {
        return $this->ordinalPosition;
    }
    
    public function setOrdinalPosition($ordinalPosition)
    {
        $this->ordinalPosition = $ordinalPosition;
        return $this;
    }
    
    public function getPositionInUniqueConstraint()
    {
        return $this->positionInUniqueConstraint;
    }
    
    public function setPositionInUniqueConstraint($positionInUniqueConstraint)
    {
        $this->positionInUniqueConstraint = $positionInUniqueConstraint;
        return $this;
    }
    
    public function getReferencedTableSchema()
    {
        return $this->referencedTableSchema;
    }

    public function setReferencedTableSchema($referencedTableSchema)
    {
        $this->referencedTableSchema = $referencedTableSchema;
        return $this;
    }
    
    public function getReferencedTableName()
    {
        return $this->referencedTableName;
    }
    
    public function setReferencedTableName($referencedTableName)
    {
        $this->referencedTableName = $referencedTableName;
        return $this;
    }
    
    public function getReferencedColumnName()
    {
        return $this->referencedColumnName;
    }
    
    public function setReferencedColumnName($referencedColumnName)
    {
        $this->referencedColumnName = $referencedColumnName;
        return $this;
    }
    
}