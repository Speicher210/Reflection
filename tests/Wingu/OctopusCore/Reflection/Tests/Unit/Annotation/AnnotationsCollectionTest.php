<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Annotation;

use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;
use Wingu\OctopusCore\Reflection\Annotation\AnnotationsCollection;
use Wingu\OctopusCore\Reflection\Annotation\TagMapper;

class AnnotationsCollectionTest extends TestCase {

    public function testEmptyComment() {
        $ac = new AnnotationsCollection('');
        $this->assertCount(0, $ac->getAnnotations());
    }

    public function getDataNoAnnotations() {
        return array(
            array('/**/'), array('/***/'),array('/** */'),
            array('/** one line */'),array('/** one @line */'),array('/** Description @tag name */'),
            array('/**
                   * test @tag
                   */'
            ),
            array('/**
                   * test @tag ("a")
                   */'
            ),
            array('/**
               * test
               *
               * long description @tag ("a")
               */'
            ),
        );
    }

    /**
     * @dataProvider getDataNoAnnotations
     */
    public function testNoAnnotations($comment) {
        $ac = new AnnotationsCollection($comment);
        $this->assertCount(0, $ac->getAnnotations());
    }

    public function getDataOneAnnotation() {
        return array(
            array('/**
                    *@directAnnotation test1
                    */', 'directAnnotation', 'test1'),
            array('/**
                    * Short description only + no space
                    *@sdnp test test test 123 test
                    */', 'sdnp', 'test test test 123 test'),
            array('/**
                    * Short description + long description + no space
                    * Long description
                    * Long description.
                    *@sdnp test test test 123 test
                    */', 'sdnp', 'test test test 123 test'),
            array('/**
                    * Short description + long description + space
                    * Long description
                    * Long description.
                    *
                    * @sdnp test test test 123 test
                    */', 'sdnp', 'test test test 123 test'),
        );
    }

    public function getDataMultipleAnnotations() {
        return array(
    		array('/**
                * @myTag test1
                * @myTag test2
                */', 'myTag', 2),
            array('/**
                * @myTag2 test1
                * @myTag2 test2
                * @myTag2 test3
                */', 'myTag2', 3),
        );
    }

    /**
     * @dataProvider getDataOneAnnotation
     */
    public function testOneAnnotation($comment, $tag, $decription) {
        $ac = new AnnotationsCollection($comment);
        $annotations = $ac->getAnnotations();
        $this->assertCount(1, $annotations);
    }

    public function testSetTagMapper() {
        $tm = $this->getMock('Wingu\OctopusCore\Reflection\Annotation\TagMapper');
        $ac = new AnnotationsCollection('');
        $ac->setTagMapper($tm);

        $this->assertEquals($tm, $ac->getTagMapper());
    }

    public function testGetAnnotationClassWithTagMapper() {
        $ac = new AnnotationsCollection('');
        $tm = $this->getMock('Wingu\OctopusCore\Reflection\Annotation\TagMapper', ['hasMappedTag', 'getMappedTag']);
        $tm->expects($this->any())
            ->method('hasMappedTag')
            ->will($this->returnValue(true));
        $tm->expects($this->any())
            ->method('getMappedTag')
            ->will($this->returnValue('MyTagClass'));

        $ac->setTagMapper($tm);

        $expected = $this->callMethod($ac, 'getAnnotationClass', ['tag']);
        $this->assertSame($expected, 'MyTagClass');
    }

    public function getDataPredefinedTags() {
        return array(
            ['param', 'Wingu\OctopusCore\Reflection\Annotation\Tags\ParamTag'],
            ['return', 'Wingu\OctopusCore\Reflection\Annotation\Tags\ReturnTag'],
            ['var', 'Wingu\OctopusCore\Reflection\Annotation\Tags\VarTag']
        );
    }

    /**
     * @dataProvider getDataPredefinedTags
     */
    public function testGetAnnotationClassWithSomeDefaultTag($tag, $expectedClass) {
        $ac = new AnnotationsCollection('');

        $expected = $this->callMethod($ac, 'getAnnotationClass', [$tag]);
        $this->assertSame($expected, $expectedClass);
    }

    public function testGetAnnotationClassWithBaseTag() {
    	$ac = new AnnotationsCollection('');

    	$expected = $this->callMethod($ac, 'getAnnotationClass', ['nonpredefinedtag']);
    	$this->assertSame($expected, 'Wingu\OctopusCore\Reflection\Annotation\Tags\BaseTag');
    }

    /**
     * @dataProvider getDataPredefinedTags
     */
    public function testGetAnnotationClassPriorityOfTagMapper($tag) {
        $ac = new AnnotationsCollection('');
        $tm = $this->getMock('Wingu\OctopusCore\Reflection\Annotation\TagMapper', ['hasMappedTag', 'getMappedTag']);
        $tm->expects($this->any())
            ->method('hasMappedTag')
            ->will($this->returnValue(true));
        $tm->expects($this->any())
            ->method('getMappedTag')
            ->will($this->returnValue('MyTagClass'.$tag));

        $ac->setTagMapper($tm);

        $expected = $this->callMethod($ac, 'getAnnotationClass', [$tag]);
        $this->assertSame($expected, 'MyTagClass'.$tag);
    }

    public function testHasAnnotationTagFalse() {
        $ac = new AnnotationsCollection('');
        $this->assertFalse($ac->hasAnnotationTag('myCustomTag'));
        $this->assertFalse($ac->hasAnnotationTag('base'));
    }

    /**
     * @dataProvider getDataOneAnnotation
     */
    public function testHasAnnotationTag($comment, $tag) {
    	$ac = new AnnotationsCollection($comment);
    	$this->assertTrue($ac->hasAnnotationTag($tag));
    }

    /**
     * @dataProvider getDataOneAnnotation
     */
    public function testGetAnnotationOneAnnotation($comment, $tag) {
    	$ac = new AnnotationsCollection($comment);
    	$annotation = $ac->getAnnotation($tag);
    	$this->assertCount(1, $annotation);
    	$this->assertSame($tag, $annotation[0]->getTagName());
    }

    /**
     * @dataProvider getDataMultipleAnnotations
     */
    public function testGetAnnotationMultipleAnnotation($comment, $tag, $count) {
    	$ac = new AnnotationsCollection($comment);
    	$annotation = $ac->getAnnotation($tag);
    	$this->assertCount($count, $annotation);
    	$this->assertSame($tag, $annotation[0]->getTagName());
    }

    /**
     * @expectedException Wingu\OctopusCore\Reflection\Annotation\Exceptions\OutOfBoundsException
     */
    public function testGetAnnotationNotFound() {
        $ac = new AnnotationsCollection('');
        $ac->getAnnotation('tag');
    }
}