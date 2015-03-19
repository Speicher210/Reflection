<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit;

use Wingu\OctopusCore\Reflection\ReflectionDocComment;

class ReflectionDocCommentTest extends TestCase {

    public function getDataOriginalBlock() {
        return array([''], ['//'], ['/**/'], ['/***/'], ['test'], ['/**//**/'],["/**\n* Test\n*/"],["/**\n* Test\n * @tag descr\n */"]);
    }

    /**
     * @dataProvider getDataOriginalBlock
     */
    public function testGetOriginalDocBlock($comment) {
        $docComment = new ReflectionDocComment($comment);
        $this->assertSame($comment, $docComment->getOriginalDocBlock());
    }

    /**
     * @dataProvider getDataOriginalBlock
     */
    public function testToString($comment) {
    	$docComment = new ReflectionDocComment($comment);
    	$this->assertSame($comment, (string)$docComment);
    }

    public function getDataShortDescription() {
        return array(
		    ['', null],['test', 'test'],['@param', null],['* @param', null],
		    ["/*\n*\n*/", null],["/*\n* Test\n*/", 'Test'],
		    ["/******\n * Test\n ******/", 'Test'],
		    ["/******\n * Test @param test.\n ******/", 'Test @param test.'],
		    ["/**\n * Test \n@param test.\n ******/", 'Test'],
		);
    }

    /**
     * @dataProvider getDataShortDescription
     */
    public function testGetShortDescription($comment, $expected) {
    	$docComment = new ReflectionDocComment($comment);
    	$this->assertSame($expected, $docComment->getShortDescription());
    }

    public function getDataLongDescription() {
    	return array(
			['', null],['test', null],
			["/*\n* Test\n*/", null],
			["/******\n * Test\n\n\n ******/", null],
			["/******\n * Test @param test.\n ******/", null],
			["/**\n * Test \n@param test.\n ******/", null],
			["/**\n * Test \n * One line description w/ star \n ******/", 'One line description w/ star'],
			["/**\n * Test \n One line description no star \n ******/", 'One line description no star'],
			["/**\n * Test \n 3 line description no star\nLine 2\nLine3 \n ******/", "3 line description no star\nLine 2\nLine3"],
			["/**\n * Test \n* 3 line description w/ star\n*Line 3\n * Line 4 \n */", "3 line description w/ star\nLine 3\nLine 4"],
			["/**\n * Test \n* 5 line description remove empty lines \n\n*Line 3\n\n * Line 4*/", "5 line description remove empty lines\nLine 3\nLine 4"],
    	);
    }

    /**
     * @dataProvider getDataLongDescription
     */
    public function testGetLongDescription($comment, $expected) {
    	$docComment = new ReflectionDocComment($comment);
    	$this->assertSame($expected, $docComment->getLongDescription());
    }

    public function getDataFullDescription() {
    	return array(
			['', null],['test', 'test'],
			["/*\n* Test\n*/", 'Test'],
			["/******\n * Test\n\n\n ******/", 'Test'],
			["/******\n * Test @param test.\n ******/", 'Test @param test.'],
			["/**\n * Test \n@param test.\n ******/", 'Test'],
			["/**\n * Test \n * One line description w/ star \n ******/", "Test\n\nOne line description w/ star"],
			["/**\n * Test \n One line description no star \n ******/", "Test\n\nOne line description no star"],
			["/**\n * Test \n 3 line description no star\nLine 2\nLine3 \n ******/", "Test\n\n3 line description no star\nLine 2\nLine3"],
			["/**\n * Test @param test. \n* 3 line description w/ star\n*Line 3\n * Line 4 \n */", "Test @param test.\n\n3 line description w/ star\nLine 3\nLine 4"],
			["/**\n * Test @param test. \n* 5 line description remove empty lines \n\n*Line 3\n\n * Line 4  */", "Test @param test.\n\n5 line description remove empty lines\nLine 3\nLine 4"],
    	);
    }

    /**
     * @dataProvider getDataFullDescription
     */
    public function testGetFullDescription($comment, $expected) {
    	$docComment = new ReflectionDocComment($comment);
    	$this->assertSame($expected, $docComment->getFullDescription());
    }

    /**
     * @dataProvider getDataOriginalBlock
     */
    public function testGetAnnotationsCollection($comment) {
    	$docComment = new ReflectionDocComment($comment);
    	$this->assertInstanceOf('Wingu\OctopusCore\Reflection\Annotation\AnnotationsCollection', $docComment->getAnnotationsCollection());
    }

    public function getDataIsEmpty() {
        return array(
            ['', true], ['  ', true], [null, true],
            ['test', false], ["/*\n* Test\n*/", false]
        );
    }

    /**
     * @dataProvider getDataIsEmpty
     */
    public function testIsEmpty($comment, $expected) {
        $docComment = new ReflectionDocComment($comment);
        $this->assertSame($expected, $docComment->isEmpty());
    }
}