<?php

namespace Zend\Module\Consumer;

interface ServiceProvider
{
    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     * 
     * @return ServiceProvider/ServiceCollection
     */
    public function getServices();
}
