<?php

namespace ZendTest\ServiceLocator\TestAsset;

use Zend\ServiceLocator\ServiceLocatorAwareInterface,
    Zend\ServiceLocator\ServiceLocatorInterface;

class ServiceAwareService implements ServiceLocatorAwareInterface
{
    public $sl = null;
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->sl = $serviceLocator;
    }
}