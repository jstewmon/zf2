<?php

namespace Zend\Db\Metadata;

use Zend\Db\Adapter\Adapter,
    Zend\Db\Adapter\Driver;

class Metadata implements MetadataInterface
{

    protected $adapter = null;
    protected $source = null;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->source = $this->createSourceFromAdapter($adapter);
    }

    protected function createSourceFromAdapter(Adapter $adapter)
    {
        switch ($adapter->getPlatform()->getName()) {
            case 'MySQL':
            case 'SQLServer':
            case 'SQLite':
                return new Source\InformationSchemaMetadata($adapter);
        }

        throw new \Exception('cannot create source from adapter');
    }

    // @todo methods

}