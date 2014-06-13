<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\ReflectionClass;

use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;
use Wingu\OctopusCore\Reflection\ReflectionClass;
use Wingu\OctopusCore\Reflection\ReflectionMethod;

/**
 * Test for ReflectionClassMethod.
 */
class ReflectionClassMethodsTest extends TestCase {

    public function testGetConstructorWithConstructorReturnConstructor() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ClassWithConstructor');
        $actualConstructor = $reflection->getConstructor();
        $this->assertEquals('__construct', $actualConstructor->getName());
        $this->assertInstanceOf("ReflectionMethod", $actualConstructor);
    }

    public function testGetMethod() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\GetMethodClass');
        $this->assertInstanceOf('\Wingu\OctopusCore\Reflection\ReflectionMethod', $reflection->getMethod('getMethodPublic'));
        $this->assertInstanceOf('\Wingu\OctopusCore\Reflection\ReflectionMethod', $reflection->getMethod('getMethodProtected'));
        $this->assertInstanceOf('\Wingu\OctopusCore\Reflection\ReflectionMethod', $reflection->getMethod('getMethodPrivate'));
    }

    public function testGetMethods() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\GetMethodClass');

        $methods = $reflection->getMethods();

        $this->assertCount(9, $methods);
        $this->assertContainsOnlyInstancesOf('Wingu\OctopusCore\Reflection\ReflectionMethod', $methods);
    }

    public function testGetMethodsCorrectMethod() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\GetMethodClass');

        $methods = $reflection->getMethods();
        $this->assertSame('__construct', $methods[0]->getName());
        $this->assertSame('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\GetMethodClass', $methods[0]->getDeclaringClass()->getName());
    }

    public function testGetMethodsFilter() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\GetMethodClass');

        ReflectionMethod::IS_ABSTRACT;

        $methodsPublic = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        $this->assertCount(6, $methodsPublic, 'Failed getting the public methods.');

        $methodsProtected = $reflection->getMethods(ReflectionMethod::IS_PROTECTED);
        $this->assertCount(1, $methodsProtected, 'Failed getting the protected methods.');

        $methodsPrivate = $reflection->getMethods(ReflectionMethod::IS_PRIVATE);
        $this->assertCount(2, $methodsPrivate, 'Failed getting the private methods.');

        $methodsStatic = $reflection->getMethods(ReflectionMethod::IS_STATIC);
        $this->assertCount(3, $methodsStatic, 'Failed getting the static methods.');

        $methodsFinal = $reflection->getMethods(ReflectionMethod::IS_FINAL);
        $this->assertCount(3, $methodsFinal, 'Failed getting the final methods.');

        $reflectionAbstract = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\AbstractClass');
        $methodsAbstract = $reflectionAbstract->getMethods(ReflectionMethod::IS_ABSTRACT);
        $this->assertCount(1, $methodsAbstract, 'Failed getting the abstract methods.');

        $methodsFPS = $reflection->getMethods(ReflectionMethod::IS_FINAL | ReflectionMethod::IS_PRIVATE | ReflectionMethod::IS_STATIC);
        $this->assertCount(5, $methodsFPS, 'Failed getting the final or private or static methods.');
    }

    public function testGetOwnMethodsWithNoOwnMethodsReturnsEmptyArray() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\EmptyClass');
        $methods = $reflection->getOwnMethods();
        $this->assertEmpty($methods);
    }

    public function testGetOwnMethodsWithOwnMethodsReturnsArray() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2');
        $actual = $reflection->getOwnMethods();
        $expected = array(new ReflectionMethod('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2', "method2"));
        $this->assertEquals($expected, $actual);
    }

    public function testGetOwnMethodsWithOwnMethodsAndFilterReturnsEmptyArray() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass1');
        $actual = $reflection->getOwnMethods(ReflectionMethod::IS_STATIC);
        $this->assertEmpty($actual);
    }

    public function testHasOwnMethodWithEmptyNameReturnFalse() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\EmptyClass');
        $actual = $reflection->hasOwnMethod('');
        $this->assertFalse($actual);
    }

    public function testHasOwnMethodWithParentMethodNameReturnFalse() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2');
        $actual = $reflection->hasOwnMethod('method1');
        $this->assertFalse($actual);
    }

    public function testHasOwnMethodWithOwnMethodNameReturnTrue() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2');
        $actual = $reflection->hasOwnMethod('method2');
        $this->assertTrue($actual);
    }
}