<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\ReflectionClass;

use Wingu\OctopusCore\Reflection\ReflectionClass;
use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;

class ReflectionClassExtensionTest extends TestCase
{

    public function testGetExtension()
    {
        $reflection = new ReflectionClass('\ReflectionClass');
        $extension = $reflection->getExtension();
        $this->assertInstanceOf('\Wingu\OctopusCore\Reflection\ReflectionExtension', $extension);

        $reflection = new ReflectionClass('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\AbstractClass');
        $extension = $reflection->getExtension();
        $this->assertNull($extension);
    }
}
