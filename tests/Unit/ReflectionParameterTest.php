<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit;

use Wingu\OctopusCore\Reflection\ReflectionParameter;

class ReflectionParameterTest extends TestCase
{

    public function testGetClass()
    {
        $reflection = new ReflectionParameter('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\testFunction3',
            'param1');
        $this->assertNull($reflection->getClass());

        $reflection = new ReflectionParameter('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\testFunction3',
            'param2');
        $this->assertNull($reflection->getClass());

        $reflection = new ReflectionParameter('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\testFunction3',
            'param3');
        $this->assertInstanceOf('Wingu\OctopusCore\Reflection\ReflectionClass', $reflection->getClass());
    }

    public function testGetDeclaringClassSimpleFunction()
    {
        $reflection = new ReflectionParameter('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\testFunction3',
            'param1');
        $this->assertNull($reflection->getDeclaringClass());

        $reflection = new ReflectionParameter('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\testFunction3',
            'param2');
        $this->assertNull($reflection->getDeclaringClass());

        $reflection = new ReflectionParameter('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\testFunction3',
            'param3');
        $this->assertNull($reflection->getDeclaringClass());
    }

    public function testGetDeclaringClassFromClasMethod()
    {
        $reflection = new ReflectionParameter(array(
            'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass3',
            'testMethod1'
        ), 'param1');
        $this->assertInstanceOf('Wingu\OctopusCore\Reflection\ReflectionClass', $reflection->getDeclaringClass());

        $reflection = new ReflectionParameter(array(
            'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass3',
            'testMethod1'
        ), 'param2');
        $this->assertInstanceOf('Wingu\OctopusCore\Reflection\ReflectionClass', $reflection->getDeclaringClass());

        $reflection = new ReflectionParameter(array(
            'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass3',
            'testMethod1'
        ), 'param3');
        $this->assertInstanceOf('Wingu\OctopusCore\Reflection\ReflectionClass', $reflection->getDeclaringClass());
    }

    public function testGetDeclaringFunction()
    {
        $reflection = new ReflectionParameter(array(
            'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass3',
            'testMethod1'
        ), 'param1');
        $this->assertInstanceOf('Wingu\OctopusCore\Reflection\ReflectionMethod', $reflection->getDeclaringFunction());

        $reflection = new ReflectionParameter('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\testFunction3',
            'param3');
        $this->assertInstanceOf('Wingu\OctopusCore\Reflection\ReflectionFunction', $reflection->getDeclaringFunction());
    }

}
