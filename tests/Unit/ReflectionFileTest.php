<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit;

use Wingu\OctopusCore\Reflection\ReflectionFile;

class ReflectionFileTest extends TestCase
{

    public function testReflectFileWithSingleNamespaceAndNoUses()
    {
        $reflectedFilePath = __DIR__ . '/Fixtures/TestReflectFileWithSingleNamespaceAndNoUses.php';

        $reflectionFile = new ReflectionFile($reflectedFilePath);
        $expectedNamespaces = array('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures');

        $this->assertSame($expectedNamespaces, $reflectionFile->getNamespaces());
    }

    public function testReflectFileWithMultipleNamespaces()
    {
        $reflectedFilePath = __DIR__ . '/Fixtures/TestReflectFileWithMultipleNamespaces.php';

        $reflectionFile = new ReflectionFile($reflectedFilePath);
        $expectedNamespaces = array(
            '\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures',
            '\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\Test'
        );

        $this->assertSame($expectedNamespaces, $reflectionFile->getNamespaces());
    }

    public function testReflectFileWithNoNamespaceUseStatementsButWithTraitUseStatements()
    {
        $reflectedFilePath = __DIR__ . '/Fixtures/TestReflectFileWithNoNamespaceUseStatementsButWithTraitUseStatements.php';

        $reflectionFile = new ReflectionFile($reflectedFilePath);
        $expectedNamespaces = array(
            '\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures' => array()
        );

        $this->assertSame($expectedNamespaces, $reflectionFile->getUses());
    }

    public function testReflectFileWithUseStatementsAndOneObject()
    {
        $reflectedFilePath = __DIR__ . '/Fixtures/TestReflectFileWithUseStatementsAndOneObject.php';

        $expectedNamespaces = array('\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures');
        $expectedUses = array(
            '\Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures' =>
                array(
                    '\stdClass' => '\stdClass',
                    'stdClassAlias' => '\stdClass'
                )
        );

        $reflectionFile = new ReflectionFile($reflectedFilePath);

        $this->assertSame($expectedNamespaces, $reflectionFile->getNamespaces());

        $this->assertSame($expectedUses, $reflectionFile->getUses());

        $objects = $reflectionFile->getObjects();
        $this->assertCount(1, $objects);
        $this->assertInstanceOf('Wingu\OctopusCore\Reflection\ReflectionClass', $objects[0]);
    }

    public function testResolveFqnToAlias()
    {
        $mock = $this->getMockBuilder('Wingu\OctopusCore\Reflection\ReflectionFile')
            ->setMethods(array('getUses'))
            ->disableOriginalConstructor()->getMock();

        $getUsesReturn = array(
            '\Test\NS' => array(
                '\Test\A\Class1' => '\Test\A\Class1',
                'Class2Alias' => '\Test\A\Class2'
            )
        );
        $mock->expects($this->any())->method('getUses')->willReturn($getUsesReturn);

        $this->assertSame('Class1', $mock->resolveFqnToAlias('\Test\A\Class1'));
        $this->assertSame('Class2Alias', $mock->resolveFqnToAlias('\Test\A\Class2'));
        $this->assertSame('\Test\NotInUse\Class1', $mock->resolveFqnToAlias('\Test\NotInUse\Class1'));
    }
}
