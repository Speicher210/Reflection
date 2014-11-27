<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures;

use stdClass;
use stdClass as stdClassAlias;

class TestReflectFileWithUseStatementsAndOneObject {

    public $stdClass;

    public $stdClassAlias;

    /**
     * Constructor.
     *
     * @param stdClass $stdClass Test parameter.
     * @param stdClassAlias $stdClassAlias Test parameter with alias type hint.
     */
    public function __construct(stdClass $stdClass, stdClassAlias $stdClassAlias) {
        $this->stdClass = $stdClass;
        $this->stdClassAlias = $stdClassAlias;
    }
}
