<?php

namespace Zend\Mvc;

use Zend\Stdlib\ResponseDescription;

class Request implements ResponseDescription
{
    public function __construct(RequestDescription $serverResponse) {}
    
    public function canAcceptHeaders() {}
    public function startStreaming() {}
    public function startOutputBuffer() {}
    public function endOutputBuffer() {}
    
    // convienence
    public function isHttp() {}
    public function isCli() {}
    public function isTest() {}
    
    public function http() {}
    public function cli() {}
    public function test() {} // ?
}