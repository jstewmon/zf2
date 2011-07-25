<?php

namespace Zend\Mvc;

use Zend\Stdlib\RequestDescription;

class Request implements RequestDescription
{
    
    protected $serverRequest = null;
    
    public function __construct(RequestDescription $serverRequest) {}
    public function getServerRequest() {}
    public function isHttp() {}
    public function getModuleName() {}
    public function getModuleNamespace() {}
    public function getControllerName() {}
    public function getActionName() {}
    
    // convienence
    public function isHttp() {}
    public function isCli() {}
    public function isTest() {}
    
    public function http() {}
    public function cli() {}
    public function test() {} // ?
}