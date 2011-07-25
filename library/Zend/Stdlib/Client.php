<?php

namespace Zend\Stdlib;

interface Client
{
    /**
     * Enter description here ...
     * 
     * @param Zend\Http\HttpRequest $request
     * @return Zend\Http\HttpResponse $response
     */
    public function send(Request $request);
}
