<?php

namespace Zend\ServiceLocator;

interface LazyServiceInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator);
}