<?php

namespace ZendTest\ServiceLocator\Di\TestAsset;

class B
{
    public $a = null;
    public function __construct(A $a)
    {
        $this->a = $a;
    }
}