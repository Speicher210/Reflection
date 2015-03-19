<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures {

    class ReflectionClassUse {

    	use ReflectionClassUseTestTrait0, ReflectionClassUseTestTrait1;
    	use MoreNS\ReflectionClassUseTestTrait;
    	use \Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures2\ReflectionClassUseTestTrait;

    	use ReflectionClassUseTestTrait2 {
        	ReflectionClassUseTestTrait2::trait2Function2 as tf2;
        	ReflectionClassUseTestTrait2::publicFunc as protected;
        }

        use ReflectionClassUseTestTrait3;

    }

    trait ReflectionClassUseTestTrait0 {}
    trait ReflectionClassUseTestTrait1 {}
    trait ReflectionClassUseTestTrait2 {
    	public function trait2Function2() {}
    	public function publicFunc() {}
    }
    trait ReflectionClassUseTestTrait3 {}
}

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures\MoreNS {
    trait ReflectionClassUseTestTrait {}
}

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures2 {
    trait ReflectionClassUseTestTrait {}
}