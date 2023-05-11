<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit;

use Wingu\OctopusCore\Reflection\ReflectionClass;
use Wingu\OctopusCore\Reflection\ReflectionConstant;

class ReflectionConstantTest extends TestCase
{

    public function testReflectionConstantConstructWithNameDeclaringClassAndValue()
    {
        $reflectionConstant = new ReflectionConstant(
            'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass1',
            'CONSTANT1'
        );
        $this->assertEquals('CONSTANT1', $reflectionConstant->getName());
        $this->assertEquals('VALUE1', $reflectionConstant->getValue());

        $expectedDeclaringClass = new ReflectionClass('Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass1');
        $this->assertEquals($expectedDeclaringClass, $reflectionConstant->getDeclaringClass());
    }

    public function testExportWithPrintingOptionPrintsTheExport()
    {
        $this->expectOutputString('Constant [ string const CONSTANT1 ] { VALUE1 }');
        ReflectionConstant::export('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2', 'CONSTANT1');
    }

    public function testExportWithoutPrintingReturnTheExportString()
    {
        $actual = ReflectionConstant::export(
            '\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass2',
            'CONSTANT1',
            true
        );
        $expected = 'Constant [ string const CONSTANT1 ] { VALUE1 }';
        $this->assertEquals($expected, $actual);
    }

    public function testGetDocCommentWithNoCommentReturnFalse()
    {
        $reflectionConstant = new ReflectionConstant(
            'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass1',
            'CONSTANT1'
        );
        $actual = $reflectionConstant->getDocComment();
        $this->assertFalse($actual);
    }

    public function testGetDocCommentWithValidCommentReturnTheDocComment()
    {
        $reflectionConstant = new ReflectionConstant(
            'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ReflectionConstantDocComment',
            'CONSTANT2'
        );
        $actual = $reflectionConstant->getDocComment();
        $expected = "/**\n     * This is *a comment* {};\n     */";
        $this->assertEquals($expected, $actual);
    }
    
    public function testGetDocCommentWithValidCommentInheritedReturnTheDocComment()
    {
        $reflectionConstant = new ReflectionConstant(
            'Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\ImplementorClass3',
            'CONSTANT3'
            );
        $actual = $reflectionConstant->getDocComment();
        $expected = "/**\n     * This is *another comment*\n     */";
        $this->assertEquals($expected, $actual);
    }

    public function testGetDocCommentFromBuiltInClassReturnFalse()
    {
        $reflectionConstant = new ReflectionConstant('DateTime', 'ATOM');
        $this->assertFalse($reflectionConstant->getDocComment());
    }
}
