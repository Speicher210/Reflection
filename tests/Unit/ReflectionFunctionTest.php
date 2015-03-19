<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit;

use Wingu\OctopusCore\Reflection\ReflectionExtension;
use Wingu\OctopusCore\Reflection\ReflectionFunction;
use Wingu\OctopusCore\Reflection\ReflectionParameter;

require_once(__DIR__ . '/Fixtures/functions.php');

class ReflectionFunctionTest extends TestCase
{

    public function testGetParametersWithoutParametersReturnEmptyArray()
    {
        $reflectionFunction = new ReflectionFunction('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\testFunction2');
        $actual = $reflectionFunction->getParameters();
        $this->assertEmpty($actual);
    }

    public function testGetParametersWithParametersReturnArray()
    {
        $reflectionFunction = new ReflectionFunction('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\testFunction1');
        $actual = $reflectionFunction->getParameters();
        $expectedParameter1 = new ReflectionParameter('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\testFunction1',
            'param1');
        $expectedParameter2 = new ReflectionParameter('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\testFunction1',
            'param2');
        $expected = array($expectedParameter1, $expectedParameter2);
        $this->assertEquals($expected, $actual);
    }

    public function testGetExtensionWithNoExtensionReturnNull()
    {
        $reflectionFunction = new ReflectionFunction('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\testFunction1');
        $actual = $reflectionFunction->getExtension();
        $this->assertNull($actual);
    }

    public function testGetExtensionWithExtensionReturnExtensionName()
    {
        $reflectionFunction = new ReflectionFunction('php_sapi_name');
        $actual = $reflectionFunction->getExtension();
        $expected = new ReflectionExtension('standard');
        $this->assertEquals($expected, $actual);
    }

    public function getDataGetBody()
    {
        return array(
            ['testGetBodyWithNormalBody', "    echo 'body{}';"],
            ['testGetBodyBracketsOnTheSameSecondLine', "echo 'body{}';"],
            ['testGetBodyBracketsOnTheSameFirstLine', "echo 'body{}';"],
            ['testGetBodyBracketsOnTheSecondAndLastLine', "    echo 'body{}';"],
        );
    }

    /**
     * @dataProvider getDataGetBody
     */
    public function testGetBody($function, $expected)
    {
        $reflectionFunction = new ReflectionFunction('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\\' . $function);
        $this->assertSame($expected, $reflectionFunction->getBody());
    }

    /**
     * @expectedException \Wingu\OctopusCore\Reflection\Exceptions\RuntimeException
     * @expectedExceptionMessage Can not get body of a function that is internal.
     */
    public function testGetBodyInternalFunction()
    {
        $reflectionFunction = new ReflectionFunction('php_sapi_name');
        $reflectionFunction->getBody();
    }
}
