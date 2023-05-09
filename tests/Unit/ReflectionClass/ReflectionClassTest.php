<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\ReflectionClass;

use Wingu\OctopusCore\Reflection\ReflectionClass;
use Wingu\OctopusCore\Reflection\ReflectionDocComment;
use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;

/**
 * Test for ReflectionClass.
 */
class ReflectionClassTest extends TestCase
{

    public function testGetConstructorWithoutConstructorReturnsNull()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\EmptyClass');
        $actualConstructor = $reflection->getConstructor();
        $this->assertNull($actualConstructor);
    }

    public function testReflectAbstract()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\AbstractClass');
        $this->assertTrue($reflection->isAbstract(), 'Failed getting abstract class reflection.');
    }

    public function testGetParentClass()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2');
        $parentClass = $reflection->getParentClass();
        $this->assertInstanceOf('\Wingu\OctopusCore\Reflection\ReflectionClass', $parentClass);
        $this->assertSame($parentClass->getName(),
            'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass1');
    }

    public function testGetReflectionDocCommentWithNoCommentReturnEmptyDocComment()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2');
        $actual = $reflection->getReflectionDocComment();
        $expected = new ReflectionDocComment('');
        $this->assertEquals($expected, $actual);
    }

    public function testGetReflectionDocCommentWithCommentReturnDocComment()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass3');
        $actual = $reflection->getReflectionDocComment();
        $expected = new ReflectionDocComment("/**\r\n *\r\n * The short definition of test class 3.\r\n * This the long description of test class 3\r\n * @author test\r\n *\r\n */");
        $this->assertEquals($expected, $actual);
    }
}
