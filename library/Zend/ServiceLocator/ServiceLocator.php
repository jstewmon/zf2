<?php

namespace Zend\ServiceLocator;

class ServiceLocator implements ServiceLocatorInterface
{

    protected $allowOverride = false;

    /**
     * Registered services and cached values
     * 
     * @var array
     */
    protected $services = array();

    /**
     * @var array
     */
    protected $aliases = array();

    public function __construct($allowOverride = false)
    {
        if ($allowOverride) {
            $this->setAllowOverride($allowOverride);
        }
    }

    public function setAllowOverride($allowOverride)
    {
        $this->allowOverride = (bool) $allowOverride;
    }

    /**
     * Register a service with the locator
     * 
     * @param  string $name 
     * @param  mixed $service 
     * @return ServiceLocator
     */
    public function set($name, $service)
    {
        if ($this->allowOverride === false && $this->has($name)) {
            throw new Exception\DuplicateServiceNameException(
                'A service by this name or alias already exists and cannot be overridden, please use an alternate name.'
            );
        }

        if (((is_string($service) || is_array($service)) && !is_callable($service)) || (!is_object($service))) {
            throw new Exception\InvalidServiceException(
                'The provided service is neither an object nor a valid service callback.'
            );
        }

        /**
         * @todo If a service is being overwritten, destroy all previous aliases
         */

        $this->services[$name] = $service;
        return $this;
    }

    /**
     * Retrieve a registered service
     *
     * Tests first if a value is registered for the service, and, if so, 
     * returns it.
     *
     * If the value returned is a non-object callback or closure, the return
     * value is retrieved, stored, and returned. Parameters passed to the method 
     * are passed to the callback, but only on the first retrieval.
     *
     * If the service requested matches a method in the method map, the return
     * value of that method is returned. Parameters are passed to the matching
     * method.
     * 
     * @param  string $name 
     * @param  array $params 
     * @return mixed
     */
    public function get($nameOrAlias)
    {
        static $circularDependencyResolver = array();

        if ($this->hasAlias($nameOrAlias)) {
            // resolve alias to service name
            do {
                $serviceName = $this->aliases[$nameOrAlias];
            } while ($this->hasAlias($serviceName));
        } else {
            $serviceName = $nameOrAlias;
        }

        if (!isset($this->services[$serviceName])) {
            throw new Exception\InvalidServiceException('Service by name ' . $serviceName . ' does not exist');
        }

        $service = $this->services[$serviceName];

        if ($service instanceof LazyServiceInterface) {

            // lazy created services
            if (isset($circularDependencyResolver[$serviceName])) {
                throw new \Exception('Circular dependency for LazyServiceLoader was found for service ' . $serviceName);
            }
            $circularDependencyResolver[spl_object_hash($this) . '-' . $serviceName] = true;
            /* @var $service LazyServiceInterface */
            $this->services[$serviceName] = $service = $service->createService($this);
            unset($circularDependencyResolver[spl_object_hash($this) . '-' . $serviceName]);

        } elseif ($service instanceof ServiceLocatorInterface) {

            // perhaps a nested service locator (di) is responsible for this service
            /* @var $service ServiceLocatorInterface */
            $this->services[$serviceName] = $service = $service->get($service);

        } elseif ($service instanceof \Closure || (!is_object($service) && is_callable($service))) {

            // closures
            $this->services[$serviceName] = $service = call_user_func($service);

        }

        if (!is_object($service)) {
            throw new Exception\InvalidServiceException('The service returned is not an object');
        }

        // this, right here, is what service location is all about
        if ($service instanceof ServiceLocatorAwareInterface) {
            /* @var $service ServiceLocatorAwareInterface */
            $service->setServiceLocator($this);
        }

        return $service;
    }

    public function has($nameOrAlias)
    {
        return (isset($this->services[$nameOrAlias]) || isset($this->aliases[$nameOrAlias]));
    }

    public function createAlias($alias, $nameOrAlias)
    {
        if (!is_string($alias) || strlen($alias) == 0) {
            throw new Exception\InvalidServiceNameException('Invalid service name alias');
        }

        if ($this->hasAlias($alias)) {
            throw new Exception\InvalidServiceNameException('An alias by this name already exists');
        }

        if (!$this->has($nameOrAlias)) {
            throw new Exception\InvalidServiceException('A target service or target alias could not be located');
        }

        $this->aliases[$alias] = $nameOrAlias;
        return $this;
    }

    public function hasAlias($alias)
    {
        return (isset($this->aliases[$alias]));
    }
}
