<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures;

/**
 * Test function.
 *
 * @param string $param1 The first parameter.
 * @param array $param2 The second parameter.
 */
function testFunction1($param1, array $param2 = array()) {
}

function testFunction2() {
}

function testFunction3($param1, array $param2 = array(), \stdClass $param3 = null) {

}

function testGetBodyWithNormalBody() {
    echo 'body{}';
}

function testGetBodyBracketsOnTheSameSecondLine()
{echo 'body{}';}

function testGetBodyBracketsOnTheSameFirstLine(){echo 'body{}';}

function testGetBodyBracketsOnTheSecondAndLastLine()
{
    echo 'body{}';}