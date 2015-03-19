<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit;

use Wingu\OctopusCore\Reflection\ReflectionClass;
use Wingu\OctopusCore\Reflection\ReflectionProperty;

class ReflectionPropertyTest extends TestCase
{

    public function testGetDeclaringClassReturnTheDeclaringClass()
    {
        $reflectionProperty = new ReflectionProperty('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass1',
            'property3');
        $actual = $reflectionProperty->getDeclaringClass();
        $expected = new ReflectionClass('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass1');
        $this->assertEquals($expected, $actual);
    }

    public function testGetDefaultValueReturnTheDefautlValue()
    {
        $reflectionProperty = new ReflectionProperty('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass1',
            'property3');
        $actual = $reflectionProperty->getDefaultValue();
        $expected = 'default value';
        $this->assertEquals($expected, $actual);
    }
}
