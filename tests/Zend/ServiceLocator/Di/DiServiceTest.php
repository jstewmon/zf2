<?php

namespace ZendTest\ServiceLocator\DiService;

use Zend\ServiceLocator\ServiceLocator,
    Zend\ServiceLocator\Di\DiService,
    Zend\Di\Di;

class DiServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testDiServicePullsServiceFromDiContainer()
    {
        $sl = new ServiceLocator;

        $di = new Di;
        $a = new \ZendTest\ServiceLocator\Di\TestAsset\A;
        $diService = new DiService(
            $di,
            'ZendTest\ServiceLocator\Di\TestAsset\B',
            array('a' => $a)
        );

        $sl->set('b', $diService);
        $this->assertInstanceOf('ZendTest\ServiceLocator\Di\TestAsset\B', $b = $sl->get('b'));
        $this->assertSame($a, $b->a);
    }

    public function testDiServicePullsServiceFromDiContainerDependencyFromLocator()
    {
        $sl = new ServiceLocator;

        $di = new Di;
        $a = new \ZendTest\ServiceLocator\Di\TestAsset\A;
        $sl->set('ZendTest\ServiceLocator\Di\TestAsset\A', $a);

        $diService = new DiService(
            $di,
            'ZendTest\ServiceLocator\Di\TestAsset\B',
            array(),
            DiService::USE_SL_BEFORE_DI
        );

        $sl->set('b', $diService);
        $this->assertInstanceOf('ZendTest\ServiceLocator\Di\TestAsset\B', $b = $sl->get('b'));
        $this->assertSame($a, $b->a);
    }

}