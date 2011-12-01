<?php

namespace ZendTest\ServiceLocator;

use Zend\ServiceLocator\ServiceLocator;

class ServiceLocatorTest extends \PHPUnit_Framework_TestCase
{

    public function testServiceLocatorIsServiceLocator()
    {
        $this->assertInstanceOf('Zend\ServiceLocator\ServiceLocatorInterface', new ServiceLocator());
    }

    public function testServiceLocatorWillPersistServiceObject()
    {
        $sl = new ServiceLocator;
        $sl->set('someservice', ($stdClass = new \stdClass()));
        $this->assertSame($stdClass, $sl->get('someservice'));
    }

    public function testServiceLocatorWillThrowExceptionOnDuplicateName()
    {
        $sl = new ServiceLocator;
        $sl->set('someservice', ($stdClass = new \stdClass()));

        $this->setExpectedException('Zend\ServiceLocator\Exception\DuplicateServiceNameException');
        $sl->set('someservice', ($stdClass = new \stdClass()));
    }

    public function testServiceLocatorThrowsExceptionOnInvalidServiceAsString()
    {
        $sl = new ServiceLocator;

        $this->setExpectedException('Zend\ServiceLocator\Exception\InvalidServiceException');
        $sl->set('someservice', 'uncallablestringname');
    }

    public function testServiceLocatorThrowsExceptionOnInvalidServiceAsUncallable()
    {
        $sl = new ServiceLocator;

        $this->setExpectedException('Zend\ServiceLocator\Exception\InvalidServiceException');
        $sl->set('someservice', array('uncallablestringname', 'uncallablemethod'));
    }

    public function testServiceLocatorWillConstituteAClosure()
    {
        $sl = new ServiceLocator;

        $aoCallback = function() {
            return new \ArrayObject;
        };

        $sl->set('someservice', $aoCallback);
        $this->assertInstanceOf('ArrayObject', $sl->get('someservice'));
    }

    public function testServiceLocatorWillConstituteAClosureOnlyOnce()
    {
        $sl = new ServiceLocator;

        $called = 0;

        $aoCallback = function() use (&$called) {
            $called++;
            return new \ArrayObject;
        };

        $sl->set('someservice', $aoCallback);

        $this->assertInstanceOf('ArrayObject', ($one = $sl->get('someservice')));
        $this->assertInstanceOf('ArrayObject', ($two = $sl->get('someservice')));
        $this->assertSame($one, $two);
        $this->assertEquals(1, $called);
    }

    public function testServiceLocatorInjectsItselfForAwareServices()
    {
        $sl = new ServiceLocator;
        $sl->set('someservice', new TestAsset\ServiceAwareService());

        $service = $sl->get('someservice');
        $this->assertSame($sl, $service->sl);
    }

    public function testServiceLocatorAliasWorks()
    {
        $sl = new ServiceLocator;
        $sl->set('foo', ($foo = new \stdClass));
        $sl->createAlias('bar', 'foo');

        $this->assertSame($foo, $sl->get('bar'));
    }

}