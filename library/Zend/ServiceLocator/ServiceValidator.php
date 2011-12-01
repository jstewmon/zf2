<?php

namespace Zend\ServiceLocator;

class ServiceValidator
{
    protected $serviceLocator = null;
    protected $serviceToTypeMaps = array();
    protected $serviceToTypeMapOwners = array();

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function addServiceToTypeMap(array $serviceToTypeMap, $owner = null)
    {
        $this->serviceToTypeMaps[] = $serviceToTypeMap;
        $this->serviceToTypeMapOwners[] = $owner;
    }

    public function validate($returnErrorArray = false)
    {
        $errorArray = array();

        $sm = $this->serviceToTypeMaps;
        // $smo = $this->serviceToTypeMapOwners;

        for ($i = 1; $i < count($sm); $i++) { // start on second one
            for ($j = 0; $j < count($sm); $j++) {
                if ($i == $j) continue;
                foreach ($sm[$j] as $name => $type) {
                    if (isset($sm[$i][$name]) && $sm[$i][$name] != $type) {
                        $errorArray[] = 'Map tries to use the same name, but different expected types'
                            . ' for service ' . $name . ' type mismatch ' . $type . ' on ' . $sm[$i][$name];
                    }
                }
            }
        }

        $mergedServiceToTypeMap = call_user_func_array('array_merge', $this->serviceToTypeMaps);

        foreach ($mergedServiceToTypeMap as $serviceName => $type) {
            if (!$this->serviceLocator->has($serviceName)) {
                $errorArray[] = 'Service by name ' . $serviceName . ' was not found';
            }
            $service = $this->serviceLocator->get($serviceName);
            if (!$service instanceof $type) {
                $errorArray[] = 'Service by name ' . $serviceName . ' was found, but was not of type ' . $type;
            }
        }

        if ($returnErrorArray) {
            return $errorArray;
        } elseif (!empty($errorArray)) {
            return false;
        } else {
            return true;
        }

    }

}