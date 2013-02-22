<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\ReflectionClass;

use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;
use Wingu\OctopusCore\Reflection\ReflectionDocComment;
use Wingu\OctopusCore\Reflection\ReflectionClass;

class ReflectionClassTest extends TestCase {

    public function testReflectAbstract() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\AbstractClass');
        $this->assertTrue($reflection->isAbstract(), 'Failed getting abstract class reflection.');
    }

    public function testGetParentClass() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2');
        $parentClass = $reflection->getParentClass();
        $this->assertInstanceOf('\Wingu\OctopusCore\Reflection\ReflectionClass', $parentClass);
        $this->assertSame($parentClass->getName(), 'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass1');
    }

    public function testGetReflectionDocCommentWithNoCommentReturnEmptyDocComment() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2');
        $actual = $reflection->getReflectionDocComment();
        $expected = new ReflectionDocComment('');
        $this->assertEquals($expected, $actual);
    }

    public function testGetReflectionDocCommentWithCommentReturnDocComment() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass3');
        $actual = $reflection->getReflectionDocComment();
        $expected = new ReflectionDocComment("/**\n *\n * The short definition of test class 3.\n * This the long description of test class 3\n * @author test\n *\n */");
        $this->assertEquals($expected, $actual);
    }
}