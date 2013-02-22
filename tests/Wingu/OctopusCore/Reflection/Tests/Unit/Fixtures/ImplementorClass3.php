<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures;

/**
 *
 * ATENTION - DO NOT FORMAT THIS FILE - OTHERWISE GETBODY() TEST WILL FAILED
 *
 */
class ImplementorClass3 extends ImplementorClass1 implements Interface4 {

    /**
     * This is  *a comment*
     * {};
     */
    const CONSTANT2 = 'VALUE2';

    public function bracketsOnTheSameFirstLine(){echo 'body{}';}

    public function bracketsOnTheSameSecondLine()
    {echo 'body{}';}

    public function bracketOnTheFirstLineAndOnTheLastLine(){
        echo 'body{}';
    }

    public function bracketsOnTheSecondAndLastLine()
    {
echo 'body{}';}

    public function methodWithNormalBody()
    {
        echo 'test';
        echo 'test';
        echo 'test';
        return 1;
    }
}