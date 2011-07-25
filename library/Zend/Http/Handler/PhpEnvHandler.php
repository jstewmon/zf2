<?php

namespace Zend\Http\Handler;

use Zend\Http\HttpResponse,
    Zend\Http\HttpRequest,
    Zend\EventManager\EventCollection;

class PhpEnvHandler
{
    
    protected $request;
    protected $response;
    
    /*
    public function createRequest($parameters) {}
    public function getRequest() {}
    public function setRequest(HttpRequest $httpRequest) {}
    public function createResponse($parameters) {}
    public function getResponse() {}
    public function setResponse(HttpResponse $httpResponse) {}
    public function sendResponse();
    */
    
    /* or */
    
    /*
    public function __construct(HttpRequest $httpRequest, HttpResponse $httpResponse) {}
    public function prepareRequest();
    public function setupEvents(EventCollection $events);
    public function startResponseContentOutputBuffer();
    public function endResponseContentOutputBuffer();
    public function sendResponseHeadersAndStartStreaming();
    public function sendResponseHeaders();
    public function sendResponse();
    */

}
