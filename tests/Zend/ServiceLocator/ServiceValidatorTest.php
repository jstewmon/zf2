<?php

namespace ZendTest\ServiceLocator;

use Zend\ServiceLocator\ServiceLocator,
    Zend\ServiceLocator\ServiceValidator;

class ServiceValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testServiceValidatorWillValidateOnSingleValidMap()
    {
        $sl = new ServiceLocator();
        $sl->set('servicea', new TestAsset\ValidateTypeA());
        $sl->set('serviceb', new TestAsset\ValidateTypeB());
        $map = array(
            'servicea' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeA',
            'serviceb' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeB'
        );

        $validator = new ServiceValidator($sl);
        $validator->addServiceToTypeMap($map);

        $this->assertTrue($validator->validate());
        $this->assertEmpty($validator->validate(true));
    }

    public function testServiceValidatorWillInvalidateSingleInvalidMap()
    {
        $sl = new ServiceLocator();
        $sl->set('servicea', new TestAsset\ValidateTypeA());
        $map = array(
            'servicea' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeB'
        );

        $validator = new ServiceValidator($sl);
        $validator->addServiceToTypeMap($map);

        $this->assertFalse($validator->validate());

        $errors = $validator->validate(true);
        $this->assertContains(
            'Service by name servicea was found, but was not of type ZendTest\ServiceLocator\TestAsset\ValidateTypeB',
            $errors
        );
    }

    public function testServiceValidatorWillValidateOnSingleValidMapUsingAliases()
    {
        $sl = new ServiceLocator();
        $sl->set('servicea', new TestAsset\ValidateTypeA());
        $sl->createAlias('my.servicea', 'servicea');
        $sl->set('serviceb', new TestAsset\ValidateTypeB());
        $sl->createAlias('my.serviceb', 'serviceb');
        $map = array(
            'my.servicea' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeA',
            'my.serviceb' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeB'
        );

        $validator = new ServiceValidator($sl);
        $validator->addServiceToTypeMap($map);

        $this->assertTrue($validator->validate());
    }

    public function testServiceValidatorWillInvalidateInvalidMapUsingAliases()
    {
        $sl = new ServiceLocator();
        $sl->set('servicea', new TestAsset\ValidateTypeA());
        $sl->createAlias('my.servicea', 'servicea');
        $map = array(
            'servicea' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeB'
        );

        $validator = new ServiceValidator($sl);
        $validator->addServiceToTypeMap($map);

        $this->assertFalse($validator->validate());

        $errors = $validator->validate(true);
        $this->assertContains(
            'Service by name servicea was found, but was not of type ZendTest\ServiceLocator\TestAsset\ValidateTypeB',
            $errors
        );
    }

    public function testServiceValidatorWillValidateOnMultipleValidMaps()
    {
        $sl = new ServiceLocator();
        $sl->set('servicea', new TestAsset\ValidateTypeA());
        $sl->set('serviceb', new TestAsset\ValidateTypeB());
        $sl->set('servicec', new TestAsset\ValidateTypeC());

        $validator = new ServiceValidator($sl);

        $validator->addServiceToTypeMap(array(
            'servicea' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeA',
            'serviceb' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeB'
        ));
        $validator->addServiceToTypeMap(array(
            'servicea' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeA',
            'servicec' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeC'
        ));

        $this->assertTrue($validator->validate());
    }

    public function testServiceValidatorWillInvalidateMultipleInvalidMaps()
    {
        $sl = new ServiceLocator();
        $sl->set('servicea', new TestAsset\ValidateTypeA());
        $sl->set('serviceb', new TestAsset\ValidateTypeB());
        $sl->set('servicec', new TestAsset\ValidateTypeC());

        $validator = new ServiceValidator($sl);

        $validator->addServiceToTypeMap(array(
            'servicea' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeA',
            'serviceb' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeB'
        ));
        $validator->addServiceToTypeMap(array(
            'servicea' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeA',
            'servicec' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeB'
        ));

        $this->assertFalse($validator->validate());
        $errors = $validator->validate(true);
        $this->assertContains(
            'Service by name servicec was found, but was not of type ZendTest\ServiceLocator\TestAsset\ValidateTypeB',
            $errors
        );
    }

    public function testServiceValidatorWillInvalidateMultipleInvalidMapsWithConflictingNames()
    {
        $sl = new ServiceLocator();
        $sl->set('servicea', new TestAsset\ValidateTypeA());
        $sl->set('serviceb', new TestAsset\ValidateTypeB());
        $sl->set('servicec', new TestAsset\ValidateTypeC());

        $validator = new ServiceValidator($sl);

        $validator->addServiceToTypeMap(array(
            'servicea' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeA',
            'serviceb' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeB'
        ));
        $validator->addServiceToTypeMap(array(
            'servicea' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeA',
            'serviceb' => 'ZendTest\ServiceLocator\TestAsset\ValidateTypeC'
        ));

        $this->assertFalse($validator->validate());
        $errors = $validator->validate(true);
        $this->assertContains(
            'Map tries to use the same name, but different expected types for service serviceb type mismatch ZendTest\ServiceLocator\TestAsset\ValidateTypeB on ZendTest\ServiceLocator\TestAsset\ValidateTypeC',
            $errors
        );
    }

}
