<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\ReflectionClass;

use Wingu\OctopusCore\Reflection\ReflectionClass;
use Wingu\OctopusCore\Reflection\ReflectionConstant;
use Wingu\OctopusCore\Reflection\ReflectionProperty;
use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;

class ReflectionClassPropertiesTest extends TestCase
{

    public function testGetPropertyFromClass()
    {
        $abstractClassReflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\AbstractClass');
        $publicAbstractProperty = $abstractClassReflection->getProperty('publicProperty');
        $this->assertInstanceOf('\Wingu\OctopusCore\Reflection\ReflectionProperty', $publicAbstractProperty);
        $this->assertSame($publicAbstractProperty->getName(), 'publicProperty');

        $protectedAbstractProperty = $abstractClassReflection->getProperty('protectedProperty');
        $this->assertInstanceOf('\Wingu\OctopusCore\Reflection\ReflectionProperty', $protectedAbstractProperty);
        $this->assertSame($protectedAbstractProperty->getName(), 'protectedProperty');

        $privateAbstractProperty = $abstractClassReflection->getProperty('privateProperty');
        $this->assertInstanceOf('\Wingu\OctopusCore\Reflection\ReflectionProperty', $privateAbstractProperty);
        $this->assertSame($privateAbstractProperty->getName(), 'privateProperty');
    }

    public function testGetPropertiesWithNoFilterAndNoExistingPropertiesReturnEmptyArray()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\EmptyClass');
        $actual = $reflection->getProperties();
        $this->assertEmpty($actual);
    }

    public function testGetPropertiesWithNoFilterAndExistingPropertiesReturnArray()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2');
        $actual = $reflection->getProperties();
        $expectedProperty1 = new ReflectionProperty('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2',
            'property1');
        $expectedProperty2 = new ReflectionProperty('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2',
            'property2');
        $expectedProperty3 = new ReflectionProperty('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2',
            'property3');
        $expected = array($expectedProperty1, $expectedProperty2, $expectedProperty3);
        $this->assertEquals($expected, $actual);
    }

    public function testGetPropertiesWithFilterAndExistingPropertiesReturnArray()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2');
        $actual = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $expectedProperty1 = new ReflectionProperty('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2',
            'property1');
        $expectedProperty3 = new ReflectionProperty('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2',
            'property3');
        $expected = array($expectedProperty1, $expectedProperty3);
        $this->assertEquals($expected, $actual);
    }

    public function testGetOwnPropertiesWithNoFilterAndNoExistingOwnPropertiesReturnEmptyArray()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\EmptyClass');
        $actual = $reflection->getOwnProperties();
        $this->assertEmpty($actual);
    }

    public function testGetOwnPropertiesWithNoFilterAndExistingOwnPropertiesReturnArray()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2');
        $actual = $reflection->getOwnProperties();
        $expectedProperty1 = new ReflectionProperty('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2',
            'property1');
        $expectedProperty2 = new ReflectionProperty('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2',
            'property2');
        $expected = array($expectedProperty1, $expectedProperty2);
        $this->assertEquals($expected, $actual);
    }

    public function testGetOwnPropertiesWithFilterAndExistingOwnPropertiesReturnArray()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2');
        $actual = $reflection->getOwnProperties(\ReflectionMethod::IS_PUBLIC);
        $expectedProperty1 = new ReflectionProperty('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2',
            'property1');
        $expected = array($expectedProperty1);
        $this->assertEquals($expected, $actual);
    }

    public function testGetOwnConstantsWithNoConstantsReturnEmptyArray()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\EmptyClass');
        $actual = $reflection->getOwnConstants();
        $this->assertEmpty($actual);
    }

    public function testGetOwnConstantsWithParentConstantAndNoOwnConstantReturnEmptyArray()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2');
        $actual = $reflection->getOwnConstants();
        $this->assertEmpty($actual);
    }

    public function testGetOwnConstantsWithParentConstantAndOwnConstantReturnArray()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass3');
        $actual = $reflection->getOwnConstants();
        $expectedConstant2 = new ReflectionConstant('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass3',
            'CONSTANT2');
        $expected = array('CONSTANT2' => $expectedConstant2);
        $this->assertEquals($expected, $actual);
    }

    public function testGetConstantsWithNoConstantReturnEmptyArray()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\EmptyClass');
        $actual = $reflection->getConstants();
        $this->assertEmpty($actual);
    }

    public function testGetConstantsWithOnlyParentConstantReturnReflectionConstantArray()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2');
        $actual = $reflection->getConstants();
        $expected = new ReflectionConstant('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2',
            'CONSTANT1');
        $this->assertEquals(array('CONSTANT1' => $expected), $actual);
    }

    public function testGetConstantsWithConstantReturnReflectionConstantArray()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass3');
        $actual = $reflection->getConstants();
        $expectedConstant2 = new ReflectionConstant('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass3',
            'CONSTANT2');
        $expectedConstant1 = new ReflectionConstant('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass3',
            'CONSTANT1');
        $expected = array('CONSTANT2' => $expectedConstant2, 'CONSTANT1' => $expectedConstant1);
        $this->assertEquals($expected, $actual);
    }

    public function testGetOwnPropertiesAndNotTraitPropertiesWithAlias()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestTrait');
        $properties = $reflection->getOwnProperties();

        $this->assertCount(3, $properties);
    }
}
