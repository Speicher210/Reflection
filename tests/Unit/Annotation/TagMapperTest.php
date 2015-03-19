<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Annotation;

use Wingu\OctopusCore\Reflection\Annotation\TagMapper;
use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;

class TagMapperTest extends TestCase
{

    public function getDataGoodTags()
    {
        return array(['tag'], ['tagTag'], ['TagTag'], ['tag123'], ['123tag']);
    }

    public function getDataMapTagExceptionsInvalidTag()
    {
        return array([''], [' '], ['tag tag'], ['tag_tag'], ['tag-tag']);
    }

    /**
     * @dataProvider getDataGoodTags
     */
    public function testMapTag($tag)
    {
        $tagMapper = new TagMapper();
        $class = $this->getMock('\Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface');
        $tagMapper->mapTag($tag, get_class($class));
        $this->assertTrue($tagMapper->hasMappedTag($tag));
    }

    /**
     * @dataProvider getDataMapTagExceptionsInvalidTag
     * @expectedException \Wingu\OctopusCore\Reflection\Annotation\Exceptions\InvalidArgumentException
     */
    public function testMapTagExceptionsInvalidTag($tag)
    {
        $tagMapper = new TagMapper();
        $class = $this->getMock('\Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface');
        $tagMapper->mapTag($tag, get_class($class));
    }

    /**
     * @expectedException \Wingu\OctopusCore\Reflection\Annotation\Exceptions\InvalidArgumentException
     */
    public function testMapTagExceptionsInvalidClass()
    {
        $tagMapper = new TagMapper();
        $tagMapper->mapTag('tag', 'stdClass');
    }

    /**
     * @dataProvider getDataGoodTags
     */
    public function testGetMappedTags($tag)
    {
        $tagMapper = new TagMapper();
        $class = $this->getMock('\Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface');
        $tagMapper->mapTag($tag, get_class($class));

        $this->assertArrayHasKey($tag, $tagMapper->getMappedTags());
    }

    /**
     * @dataProvider getDataGoodTags
     */
    public function testGetMappedTag($tag)
    {
        $tagMapper = new TagMapper();
        $class = $this->getMock('\Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface');
        $tagMapper->mapTag($tag, get_class($class));

        $this->assertSame($tagMapper->getMappedTag($tag), get_class($class));
    }

    /**
     * @expectedException \Wingu\OctopusCore\Reflection\Annotation\Exceptions\OutOfBoundsException
     */
    public function testGetMappedTagException()
    {
        $tagMapper = new TagMapper();
        $class = $this->getMock('\Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface');
        $tagMapper->mapTag('tag', get_class($class));

        $tagMapper->getMappedTag('tag2');
    }

    public function testMergeTagMapperNoOverwrite()
    {
        $tagClass1 = $this->getMock('\Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface', array(), array(),
            'Mock_tagMapperWithOverwrite1');
        $tagClass1 = get_class($tagClass1);

        $tagClass2 = $this->getMock('\Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface', array(), array(),
            'Mock_tagMapperWithOverwrite2');
        $tagClass2 = get_class($tagClass2);

        $tagMapper1 = new TagMapper();
        $tagMapper1->mapTag('tag1', $tagClass1);
        $tagMapper1->mapTag('tag2', $tagClass1);

        $tagMapper2 = new TagMapper();
        $tagMapper2->mapTag('tag1', $tagClass2);
        $tagMapper2->mapTag('tag3', $tagClass2);

        $tagMapper1->mergeTagMapper($tagMapper2, false);

        $this->assertSame($tagClass1, $tagMapper1->getMappedTag('tag1'));
        $this->assertSame($tagClass1, $tagMapper1->getMappedTag('tag2'));
        $this->assertSame($tagClass2, $tagMapper1->getMappedTag('tag3'));
    }

    public function testMergeTagMapperWithOverwrite()
    {
        $tagClass1 = $this->getMock('\Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface', array(), array(),
            'Mock_tagMapperWithOverwrite1');
        $tagClass1 = get_class($tagClass1);

        $tagClass2 = $this->getMock('\Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface', array(), array(),
            'Mock_tagMapperWithOverwrite2');
        $tagClass2 = get_class($tagClass2);

        $tagMapper1 = new TagMapper();
        $tagMapper1->mapTag('tag1', $tagClass1);
        $tagMapper1->mapTag('tag2', $tagClass1);

        $tagMapper2 = new TagMapper();
        $tagMapper2->mapTag('tag1', $tagClass2);
        $tagMapper2->mapTag('tag3', $tagClass2);

        $tagMapper1->mergeTagMapper($tagMapper2);

        $this->assertSame($tagClass2, $tagMapper1->getMappedTag('tag1'));
        $this->assertSame($tagClass1, $tagMapper1->getMappedTag('tag2'));
        $this->assertSame($tagClass2, $tagMapper1->getMappedTag('tag3'));
    }

}
