<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\ReflectionClass;

use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;
use Wingu\OctopusCore\Reflection\ReflectionClass;

class ReflectionClassInterfacesTest extends TestCase {

    public function testGetInterfacesWithClassWithoutInterfacesReturnEmptyArray() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass1');
        $this->assertEmpty($reflection->getInterfaces());
    }

    public function testGetInterfacesWithClassImplementsThreeInterfacesReturnsReflectionClasses() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass1');
        $interfaces = $reflection->getInterfaces();

        $expectedInterface1 = new ReflectionClass('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\Interface1');

        $expectedInterface2 = new ReflectionClass('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\Interface2');

        $expectedInterface3 = new ReflectionClass('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\Interface3');

        $expectedArray = array('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\Interface1' => $expectedInterface1, 'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\Interface2' => $expectedInterface2,
                'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\Interface3' => $expectedInterface3);

        $this->assertEquals($expectedArray, $interfaces);
    }

    public function testGetOwnInterfacesWithoutInterfacesReturnEmptyArray() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass1');
        $this->assertEmpty($reflection->getOwnInterfaces());
    }

    public function testGetOwnInterfacesWithClassImplementsTwoOwnInterfacesReturnsReflectionClasses() {
        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2');

        $interfaces = $reflection->getOwnInterfaces();

        $expectedInterface4 = new ReflectionClass('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\Interface4');

        $expectedArray = array('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\Interface4' => $expectedInterface4);

        $this->assertEquals($expectedArray, $interfaces);
    }
}