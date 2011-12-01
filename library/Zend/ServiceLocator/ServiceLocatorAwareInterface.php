<?php

namespace Zend\ServiceLocator;

interface ServiceLocatorAwareInterface
{
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator);
}