<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit;

use Wingu\OctopusCore\Reflection\ReflectionClassUse;

class ReflectionClassUseTest extends TestCase {

    public function getDataTraitNames() {
        return array(
            ['Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionClassUseTestTrait0', []],
            ['Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionClassUseTestTrait1', []],
            ['Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionClassUseTestTrait2', ['ReflectionClassUseTestTrait2::trait2Function2 as tf2', 'ReflectionClassUseTestTrait2::publicFunc as protected']],
            ['Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionClassUseTestTrait3', []],

            ['MoreNS\ReflectionClassUseTestTrait', []],
            ['Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\MoreNS\ReflectionClassUseTestTrait', []],
            ['\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures2\ReflectionClassUseTestTrait', []],
        );
    }

    /**
     * @dataProvider getDataTraitNames
     */
    public function testGetConflictResolutions($traitName, $expected) {
        $reflection = new ReflectionClassUse('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionClassUse', $traitName);
        $actual = $reflection->getConflictResolutions();

        $this->assertSame($traitName, $reflection->getName());
        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException Wingu\OctopusCore\Reflection\Exceptions\InvalidArgumentException
     */
    public function testInvalidTraitName() {
    	$reflection = new ReflectionClassUse('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionClassUse', 'dummyTrait');
    }

    /**
     * @expectedException Wingu\OctopusCore\Reflection\Exceptions\InvalidArgumentException
     */
    public function testBadUseStatements() {
    	$mockReflectionClass = $this->getMock('Wingu\OctopusCore\Reflection\ReflectionClass', ['getBody'], [], '', false);
    	$mockReflectionClass->expects($this->any())->method('getBody')->will($this->returnValue('use "a";'));
    	$mock = $this->getMock('Wingu\OctopusCore\Reflection\ReflectionClassUse', null, [], '', false);
    	$this->setProperty($mock, 'declaringClass', $mockReflectionClass);
    	$this->callMethod($mock, 'findConflictResolutions');
    }

    public function testExportWithoutPrintingReturnTheExportString() {
    	$actual = ReflectionClassUse::export('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionClassUse', 'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionClassUseTestTrait0', true);
    	$expected = 'ClassUse [ trait Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionClassUseTestTrait0 ] { }';
    	$this->assertEquals($expected, $actual);
    }

    public function testExportWithPrintingOptionPrintsTheExport() {
        $this->expectOutputString('ClassUse [ trait Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionClassUseTestTrait0 ] { }');
        ReflectionClassUse::export('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionClassUse', 'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionClassUseTestTrait0');
    }

    public function testExportWithConflicResolutions() {
        $actual = ReflectionClassUse::export('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionClassUse', 'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionClassUseTestTrait2', true);
        $expected  = 'ClassUse [ trait Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionClassUseTestTrait2 ] {'.PHP_EOL;
        $expected .= 'ReflectionClassUseTestTrait2::trait2Function2 as tf2;'.PHP_EOL;
        $expected .= 'ReflectionClassUseTestTrait2::publicFunc as protected;'.PHP_EOL;
        $expected .= '}';
        $this->assertEquals($expected, $actual);
    }
}