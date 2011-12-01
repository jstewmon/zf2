<?php

namespace ZendTest\ServiceLocator\Di\TestAsset;

class B
{
    public $b = null;
    public $string = null;
    public function __construct(B $b, $string)
    {
        $this->b = $b;
    }
}