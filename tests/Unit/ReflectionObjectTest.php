<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit;

use Wingu\OctopusCore\Reflection\ReflectionObject;
use Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass1;

class ReflectionObjectTest extends TestCase {

    public function testReflectObject() {
        $object = new TestClass1();

        $reflection = new ReflectionObject($object);
        $this->assertSame($reflection->getName(), 'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\TestClass1');
    }
}