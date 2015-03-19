<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Annotation\Tags;

use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;
use Wingu\OctopusCore\Reflection\Annotation\Tags\BaseTag;

class BaseTagTest extends TestCase {

    public function getDataForAnnotationDefinition() {
        return array(
            ['tag0', null, 'tag0', null],
            ['tag1', ' ', 'tag1', null],
            ['tag2', 'description bar ', 'tag2', 'description bar'],
            ['tag3', ' description ', 'tag3', 'description']
        );
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testBaseTag($tag, $description, $expectedTag, $expectedDescription) {
        $ad = $this->getMock('Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition', ['getTag', 'getDescription'], ['']);
        $ad->expects($this->any())
            ->method('getTag')
            ->will($this->returnValue($tag));

        $ad->expects($this->any())
            ->method('getDescription')
            ->will($this->returnValue($description));

        $baseTag = new BaseTag($ad);
        $this->assertSame($expectedTag, $baseTag->getTagName());
        $this->assertSame($expectedDescription, $baseTag->getDescription());
    }
}