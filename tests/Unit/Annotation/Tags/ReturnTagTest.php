<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Annotation\Tags;

use Wingu\OctopusCore\Reflection\Annotation\Tags\ReturnTag;
use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;

class ReturnTagTest extends TestCase
{

    public function getDataForAnnotationDefinition()
    {
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
    public function testReturnTag($description, $expectedReturnType, $expectedReturnDescription)
    {
        $ad = $this->getMockBuilder('Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition')
            ->setMethods(['getTag', 'getDescription'])
            ->setConstructorArgs([''])
            ->getMock()
        ;
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

    public function testReturnTagWrongAnnotationDefinition()
    {
        $this->expectException('\Wingu\OctopusCore\Reflection\Annotation\Exceptions\InvalidArgumentException');
        $ad = $this->getMockBuilder('Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition')
            ->setMethods(['getTag'])
            ->setConstructorArgs([''])
            ->getMock()
        ;
        $ad->expects($this->any())
            ->method('getTag')
            ->will($this->returnValue('wrongtag'));

        new ReturnTag($ad);
    }
}
