<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures;

class TestClassWithOverridenMethod extends TestClass2 {

    public function testMethod1($param1, array $param2 = array()) {
    }
}