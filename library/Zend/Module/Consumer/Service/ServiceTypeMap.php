<?php

namespace Zend\Module\Consumer\Service;

class ServiceTypeMap
{
    protected $serviceTypeMap = null;

    public function __construct(array $serviceTypeMap = array())
    {
        !$serviceTypeMap ?: $this->setServiceTypeMap($serviceTypeMap);
    }

    public function setServiceTypeMap(array $serviceTypeMap)
    {
        foreach ($serviceTypeMap as $name => $type) {
            if (!is_string($name)) {
                throw new \Exception('Service array key must be a string for the service name');
            }
            if (!is_string($type) && !class_exists($type)) {
                throw new \Exception('Service type must be a valid class name.');
            }
        }
        $this->serviceTypeMap = $serviceTypeMap;
    }

    public function getServiceTypeMap()
    {
        return $this->serviceTypeMap;
    }

}