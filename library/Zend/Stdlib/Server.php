<?php

namespace Zend\Stdlib;

interface Server
{
    /**
     * 
     * @param Zend\Http\HttpRequest $httpRequest
     * @return bool
     */
    public function handle(Request $httpRequest);
}
