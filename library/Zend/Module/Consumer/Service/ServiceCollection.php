<?php

namespace Zend\Module\Consumer\Service;

class ServiceCollection
{
    protected $services = null;

    public function __construct(array $services = array())
    {
        !$services ?: $this->setServices($services);
    }

    public function setServices(array $services)
    {
        foreach ($services as $name => $service) {
            if (!is_string($name)) {
                throw new \Exception('Service array key must be a string for the service name');
            }
            if (!is_object($service)) {
                if (is_string($service) && !is_callable($service)) {
                    throw new \Exception('Service value must be an object, or something that can produce an object');    
                }
            }
        }
        $this->services = $services;
    }

    public function getServices()
    {
        return $this->services;
    }

}