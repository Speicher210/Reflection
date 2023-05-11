<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit;

use Wingu\OctopusCore\Reflection\ReflectionClass;
use Wingu\OctopusCore\Reflection\ReflectionExtension;
use Wingu\OctopusCore\Reflection\ReflectionMethod;
use Wingu\OctopusCore\Reflection\ReflectionParameter;

class ReflectionMethodTest extends TestCase
{

    public function testGetExtensionWithPHPExtensionClassReturnExtensionName()
    {
        $reflection = new ReflectionMethod('\ReflectionClass', 'getName');
        $actual = $reflection->getExtension();
        $expected = new ReflectionExtension('Reflection');
        $this->assertEquals($expected, $actual);
    }

    public function testGetExtensionWithNotPHPExtensionClassReturnNull()
    {
        $reflection = new ReflectionMethod('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\AbstractClass',
            'getMethodAbstract');
        $actual = $reflection->getExtension();
        $this->assertNull($actual);
    }

    public function testGetDeclaringClassReturnReflectionClass()
    {
        $reflection = new ReflectionMethod('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass2',
            'testMethod1');
        $actual = $reflection->getDeclaringClass();
        $expected = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass2');
        $this->assertEquals($expected, $actual);
    }

    public function testGetPrototypeReturnReflectionMethod()
    {
        $reflection = new ReflectionMethod('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClassWithOverridenMethod',
            'testMethod1');
        $actual = $reflection->getPrototype();
        $expected = new ReflectionMethod('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass2', 'testMethod1');
        $this->assertEquals($expected, $actual);
    }

    public function testGetBodyWithEmptyBodyReturnBodyString()
    {
        $reflection = new ReflectionMethod('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass1',
            'method1');
        $actual = $reflection->getBody();
        $this->assertEquals('', $actual);
    }

    public function testGetBodyWithBracketOnTheSameFirstLineReturnBodyString()
    {
        $reflection = new ReflectionMethod('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass3',
            'bracketsOnTheSameFirstLine');
        $actual = $reflection->getBody();
        $expected = 'echo \'body{}\';';
        $this->assertEquals($expected, $actual);
    }

    public function testGetBodyWithBracketsOnTheSameSecondLineReturnBodyString()
    {
        $reflection = new ReflectionMethod('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass3',
            'bracketsOnTheSameSecondLine');
        $actual = $reflection->getBody();
        $expected = 'echo \'body{}\';';
        $this->assertEquals($expected, $actual);
    }

    function testGetBodyWithBracketOnTheFirstLineAndOnTheLastLineReturnBodyString()
    {
        $reflection = new ReflectionMethod('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass3',
            'bracketOnTheFirstLineAndOnTheLastLine');
        $actual = $reflection->getBody();
        $expected = "        echo 'body{}';";
        $this->assertEquals($expected, $actual);
    }

    public function testGetBodyWithBracketsOnTheSecondAndLastLineReturnBodyString()
    {
        $reflection = new ReflectionMethod('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass3',
            'bracketsOnTheSecondAndLastLine');
        $actual = $reflection->getBody();
        $expected = "echo 'body{}';";
        $this->assertEquals($expected, $actual);
    }

    public function testGetBodyWithBodyReturnString()
    {
        $reflection = new ReflectionMethod('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass3',
            'methodWithNormalBody');
        $actual = $reflection->getBody();
        $expected = "        echo 'test';\n        echo 'test';\n        echo 'test';\n        return 1;";
        $this->assertEquals($expected, $actual);
    }

    public function testGetParametersWithNoParametersReturnEmptyArray()
    {
        $reflection = new ReflectionMethod('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass2',
            'testMethod2');
        $actual = $reflection->getParameters();
        $this->assertEmpty($actual);
    }

    public function testGetParametersWithTwoParamatersAndOneWithDefaultValueReturnReflectionParamaterArray()
    {
        $reflection = new ReflectionMethod('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass2',
            'testMethod1');
        $actual = $reflection->getParameters();
        $expectedParameter1 = new ReflectionParameter(array(
            'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass2',
            'testMethod1'
        ), 'param1');
        $expectedParameter2 = new ReflectionParameter(array(
            'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass2',
            'testMethod1'
        ), 'param2');
        $expected = array($expectedParameter1, $expectedParameter2);
        $this->assertEquals($expected, $actual);
    }

    public function testGetBodyMethodFromInternalClass()
    {
        $this->expectException('\Wingu\OctopusCore\Reflection\Exceptions\RuntimeException');
        $reflectionMethod = new ReflectionMethod('ArrayIterator', 'count');
        $reflectionMethod->getBody();
    }

    public function testGetBodyMethodFromAbstractClass()
    {
        $this->expectException('\Wingu\OctopusCore\Reflection\Exceptions\RuntimeException');
        $reflectionMethod = new ReflectionMethod('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\AbstractClass',
            'getMethodAbstract');
        $reflectionMethod->getBody();
    }

    public function testGetBodyMethodFromInterface()
    {
        $this->expectException('\Wingu\OctopusCore\Reflection\Exceptions\RuntimeException');
        $reflectionMethod = new ReflectionMethod('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\SimpleInterface',
            'simpleFunction');
        $reflectionMethod->getBody();
    }
}
