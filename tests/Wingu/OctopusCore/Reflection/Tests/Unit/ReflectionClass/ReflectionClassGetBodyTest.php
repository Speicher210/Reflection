<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\ReflectionClass;

use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;
use Wingu\OctopusCore\Reflection\ReflectionClass;

class ReflectionClassGetBodyTest extends TestCase {

    public function getDataGetBody() {
        require_once (__DIR__ . '/../Fixtures/ReflectionClassGetBody.php');
        return array(
            ['ReflectionClassGetBody1', '    public $property;'],
            ['ReflectionClassGetBody2', 'public $property;'],
            ['ReflectionClassGetBody3', 'public $property;'],
            ['ReflectionClassGetBody4', '    public $property;'],
        );
    }

    /**
     * @dataProvider getDataGetBody
     */
    public function testGetBody($class, $expected) {
    	$reflection = new ReflectionClass('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\\'.$class);
    	$this->assertSame($expected, $reflection->getBody());
    }

    /**
     * @expectedException Wingu\OctopusCore\Reflection\Exceptions\RuntimeException
     */
    public function testblaGetBodyInternalClass() {
    	$reflection = new ReflectionClass('ReflectionClass');
    	$reflection->getBody();
    }
}