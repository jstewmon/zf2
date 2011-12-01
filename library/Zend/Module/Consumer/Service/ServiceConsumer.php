<?php

namespace Zend\Module\Consumer;

interface ServiceConsumer
{
    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     * 
     * @return ServiceProvider/ServiceCollection
     */
    public function getServiceMap();
}
