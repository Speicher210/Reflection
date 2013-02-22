<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Annotation\Tags;

use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;
use Wingu\OctopusCore\Reflection\Annotation\Tags\ParamTag;

class ParamTagTest extends TestCase {

    public function getDataForAnnotationDefinition() {
        return array(
            [' ', null, null, null],
            ['type', 'type', null, null],
            [' type ', 'type', null, null],
            ['0 ', '0', null, null],
            ['type name', 'type', 'name', null],
            [' type   name ', 'type', 'name', null],
            ['type name description', 'type', 'name', 'description'],
            [' type  name  long description ', 'type', 'name', 'long description'],
        );
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testParamTag($description, $expectedParamType, $expectedParamName, $expectedParamDescription) {
        $ad = $this->getMock('Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition', ['getTag', 'getDescription'], ['']);
        $ad->expects($this->any())
            ->method('getTag')
            ->will($this->returnValue('param'));

        $ad->expects($this->any())
            ->method('getDescription')
            ->will($this->returnValue($description));

        $paramTag = new ParamTag($ad);
        $this->assertSame($expectedParamType, $paramTag->getParamType());
        $this->assertSame($expectedParamName, $paramTag->getParamName());
        $this->assertSame($expectedParamDescription, $paramTag->getParamDescription());
    }

    /**
     * @expectedException Wingu\OctopusCore\Reflection\Annotation\Exceptions\InvalidArgumentException
     */
    public function testVarTagWrongAnnotationDefinition() {
    	$ad = $this->getMock('Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition', ['getTag'], ['']);
    	$ad->expects($this->any())
        	->method('getTag')
        	->will($this->returnValue('wrongtag'));

    	$varTag = new ParamTag($ad);
    }
}