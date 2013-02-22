<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Annotation\Tags;

use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;
use Wingu\OctopusCore\Reflection\Annotation\Tags\ReturnTag;

class ReturnTagTest extends TestCase {

    public function getDataForAnnotationDefinition() {
        return array(
            [null, null, null],
            ['', null, null],
            ['0', '0', null],
            ['mytype', 'mytype', null],
            ['namespace\mytype', 'namespace\mytype', null],
            ['\namespace\mytype', '\namespace\mytype', null],
            [' namespace\mytype ', 'namespace\mytype', null],
            ['mytype mydescription', 'mytype', 'mydescription'],
            ['mytype 0', 'mytype', '0'],
            ['mytype  mydescription ', 'mytype', 'mydescription'],
            ['mytype  my long description ', 'mytype', 'my long description'],
        );
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testReturnTag($description, $expectedReturnType, $expectedReturnDescription) {
        $ad = $this->getMock('Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition', ['getTag', 'getDescription'], ['']);
        $ad->expects($this->any())
            ->method('getTag')
            ->will($this->returnValue('return'));

        $ad->expects($this->any())
            ->method('getDescription')
            ->will($this->returnValue($description));

        $returnTag = new ReturnTag($ad);
        $this->assertSame('return', $returnTag->getTagName());
        $this->assertSame($expectedReturnType, $returnTag->getReturnType());
        $this->assertSame($expectedReturnDescription, $returnTag->getReturnDescription());
    }

    /**
     * @expectedException Wingu\OctopusCore\Reflection\Annotation\Exceptions\InvalidArgumentException
     */
    public function testReturnTagWrongAnnotationDefinition() {
        $ad = $this->getMock('Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition', ['getTag'], ['']);
        $ad->expects($this->any())
            ->method('getTag')
            ->will($this->returnValue('wrongtag'));

        $returnTag = new ReturnTag($ad);
    }
}