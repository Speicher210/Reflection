<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\ReflectionClass;

use Wingu\OctopusCore\Reflection\ReflectionClass;
use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;

class ReflectionClassTraitsTest extends TestCase
{

    public function testGetTraits()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass2');
        $traits = $reflection->getTraits();

        foreach ($traits as $trait) {
            $this->assertInstanceOf('\Wingu\OctopusCore\Reflection\ReflectionClass', $trait);
            $this->assertTrue($trait->isTrait());
        }

        $this->assertCount(1, $traits);
    }

    public function testGetOwnMethodsAndNotTraitMethods()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass2');
        $methods = $reflection->getOwnMethods();

        $this->assertCount(2, $methods);

        $this->assertSame('testMethod1', $methods[0]->getName());
        $this->assertSame('testMethod2', $methods[1]->getName());
    }

    public function testGetOwnMethodsAndNotTraitMethodsWithAlias()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestTrait');
        $methods = $reflection->getOwnMethods();

        $this->assertCount(1, $methods);
        $this->assertSame('traitFunction1', $methods[0]->getName());
    }

    public function testGetUses()
    {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestTrait');
        $uses = $reflection->getUses();
        $this->assertCount(1, $uses);
        $this->assertSame('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestTrait2', $uses[0]->getName());
        $this->assertSame(['TestTrait2::trait2Function2 as tf2'], $uses[0]->getConflictResolutions());
    }
}
