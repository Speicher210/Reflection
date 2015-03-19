<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Annotation;

use Wingu\OctopusCore\Reflection\Tests\Unit\TestCase;
use Wingu\OctopusCore\Reflection\Annotation\Parser;
use Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition;

class ParserTest extends TestCase {

    public function testParseWithEmptyCommentReturnEmptyArray() {
        $parser = new Parser('');
        $actual = $parser->getFoundAnnotationDefinitions();
        $expected = array();
        $this->assertEquals($expected, $actual);
    }

    public function testParseCommentWithNoRealAnnotationsReturnEmptyArray() {
        $parser = new Parser('/** Test @annotation test a b c*/');

        $actual = $parser->getFoundAnnotationDefinitions();
        $expected = array();
        $this->assertEquals($expected, $actual);
    }

    public function testParseCommentWithSingleAnnotationWithoutValueReturnAnnotationDefinitionArray() {
        $parser = new Parser('/** Test
                * @annotationB
                */');
        $actual = $parser->getFoundAnnotationDefinitions();
        $expected = array('annotationB' => array(new AnnotationDefinition('annotationB')));
        $this->assertEquals($expected, $actual);
    }

    public function testParseCommentWithTwoSameAnnotationsWithoutValueReturnAnnotationDefinitionArray() {
        $parser = new Parser('/** Test
                * @annotationB
                * @annotationB
                */');
        $actual = $parser->getFoundAnnotationDefinitions();
        $expected = array('annotationB' => array(new AnnotationDefinition('annotationB'), new AnnotationDefinition('annotationB')));
        $this->assertEquals($expected, $actual);
    }

    public function testParseCommentWithSingleAnnotationWithValuesReturnAnnotationDefinitionArray() {
        $parser = new Parser('/** Test
                * @annotationB value1 value2 value3
                */');
        $actual = $parser->getFoundAnnotationDefinitions();
        $expected = array('annotationB' => array(new AnnotationDefinition('annotationB', 'value1 value2 value3')));
        $this->assertEquals($expected, $actual);
    }

    public function testParseCommentWithTwoSameAnnotationsWithValuesReturnAnnotationDefinitionArray() {
        $parser = new Parser('/** Test
                * @annotationB value1 value2 value3
                * @annotationB value4 value5 value6
                */');
        $actual = $parser->getFoundAnnotationDefinitions();
        $expectedAnnotation1 = new AnnotationDefinition('annotationB', 'value1 value2 value3');
        $expectedAnnotation2 = new AnnotationDefinition('annotationB', 'value4 value5 value6');

        $expected = array('annotationB' => array($expectedAnnotation1, $expectedAnnotation2));
        $this->assertEquals($expected, $actual);
    }

    public function testParseCommentWithAnnotationWithBracketValueReturnAnnotationDefinitionArray() {
        $parser = new Parser('/** Test
                * @annotationB {value1,value2}
                */');
        $actual = $parser->getFoundAnnotationDefinitions();
        $expectedAnnotation1 = new AnnotationDefinition('annotationB', '{value1,value2}');

        $expected = array('annotationB' => array($expectedAnnotation1));
        $this->assertEquals($expected, $actual);
    }

    public function testParseCommentWithAnnotationWithMultilineValueSurroundedByParenthesesReturnAnnotationDefinitionArray() {
        $parser = new Parser('/** Test
                * @annotationB("aaa
                * @someannot )
                */');
        $actual = $parser->getFoundAnnotationDefinitions();
        $expectedAnnotation1 = new AnnotationDefinition('annotationB', "(\"aaa\n@someannot )");
        $expected = array('annotationB' => array($expectedAnnotation1));
        $this->assertEquals($expected, $actual);
    }

    /**
     * @group debug
     */
    public function testParseCommentWithAnnotationWithValueSurroundedByParenthesesReturnAnnotationDefinitionArray() {
        $parser = new Parser('/** Test
                * @annotationB(aaa)
                */');
        $actual = $parser->getFoundAnnotationDefinitions();
        $expectedAnnotation1 = new AnnotationDefinition('annotationB', "(aaa)");
        $expected = array('annotationB' => array($expectedAnnotation1));
        $this->assertEquals($expected, $actual);
    }

    public function testParseCommentWithAnnotationWithValueContainingArobaseReturnAnnotationDefinitionArray() {
        $parser = new Parser('/** Test
                * @annotationB @annotation
                */');
        $actual = $parser->getFoundAnnotationDefinitions();
        $expectedAnnotation1 = new AnnotationDefinition('annotationB', "@annotation");
        $expected = array('annotationB' => array($expectedAnnotation1));
        $this->assertEquals($expected, $actual);
    }

    public function testParseCommentWithAnnotationWithNestedArrayValueReturnAnnotationDefinitionArray() {
        $parser = new Parser('/** Test
                * @annotationB(a(b))
                */');
        $actual = $parser->getFoundAnnotationDefinitions();
        $expectedAnnotation1 = new AnnotationDefinition('annotationB', "(a(b))");
        $expected = array('annotationB' => array($expectedAnnotation1));
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \Wingu\OctopusCore\Reflection\Annotation\Exceptions\RuntimeException
     */
    public function testParseCommentWithAnnotationWithMultiLineValueNotCloseNestedArrayThrowsRuntimeException() {
        $parser = new Parser('/** Test
                * @annotationB(a b
                * c d e f
                */');
        $parser->getFoundAnnotationDefinitions();
    }

    public function testParseCommentWithAnnotationWithDoubleArobaseAndValueReturnEmptyArray() {
        $parser = new Parser('/** Test
                * @@annotationB dd gg
                */');
        $actual = $parser->getFoundAnnotationDefinitions();
        $this->assertEmpty($actual);
    }
}