<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures;

class ImplementorClass1 implements Interface1, Interface3 {

    public $property3 = 'default value';

    const CONSTANT1 = 'VALUE1';
    
    /**
     * This is *another comment*
     */
    const CONSTANT3 = 'VALUE1_WITH_COMMENTS';

    public function method1() {
    }
}