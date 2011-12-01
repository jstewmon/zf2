<?php
namespace Zend\ServiceLocator;

interface ServiceLocatorInterface
{

    public function get($nameOrAlias);
    public function set($name, $service);
    public function has($nameOrAlias);
    public function createAlias($alias, $nameOrAlias);

}
