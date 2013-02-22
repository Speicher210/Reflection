<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Annotation\Tags;

use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;
use Wingu\OctopusCore\Reflection\Annotation\Tags\VarTag;

class VarTagTest extends TestCase {

    public function getDataForAnnotationDefinition() {
        return array(
            [null, null],
            ['', null],
            ['  ', null],
            ['mytype', 'mytype'],
            ['namespace\mytype', 'namespace\mytype'],
            ['\namespace\mytype', '\namespace\mytype'],
            ['mytype mydescription', 'mytype mydescription'],
            [' mytype  mydescription ', 'mytype  mydescription'],
            ['mytype  my long description ', 'mytype  my long description'],
        );
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testVarTag($description, $expectedVarType) {
        $ad = $this->getMock('Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition', ['getTag', 'getDescription'], ['']);
        $ad->expects($this->any())
            ->method('getTag')
            ->will($this->returnValue('var'));

        $ad->expects($this->any())
            ->method('getDescription')
            ->will($this->returnValue($description));

        $returnTag = new VarTag($ad);
        $this->assertSame('var', $returnTag->getTagName());
        $this->assertSame($expectedVarType, $returnTag->getVarType());
    }

    /**
     * @expectedException Wingu\OctopusCore\Reflection\Annotation\Exceptions\InvalidArgumentException
     */
    public function testVarTagWrongAnnotationDefinition() {
    	$ad = $this->getMock('Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition', ['getTag'], ['']);
    	$ad->expects($this->any())
        	->method('getTag')
        	->will($this->returnValue('wrongtag'));

    	$varTag = new VarTag($ad);
    }
}