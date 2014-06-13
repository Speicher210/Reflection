<?php

namespace Wingu\Tests\OctopusCore\Unit\Reflection\Fixtures2;

class ReflectionConstantDocComment {

    /**
     * Nothing here
     */
    const CONSTANT2 = 'VALUE2';

    public function methodWithNormalBody() {
        echo self::CONSTANT2;
    }
}

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures;

class ReflectionConstantDocComment {

    /**
     * This is constant three
     */
    const CONSTANT3 = 'VALUE3';

    /**
     * This is *a comment* {};
     */
    const CONSTANT2 = 'VALUE2';

    public function methodWithNormalBody() {
        echo self::CONSTANT2;
    }
}