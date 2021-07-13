<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures;

class GetMethodClass {

    public function __construct() {
    }

    function getMethodNoVisibilityDefined() {
    }

    public function getMethodPublic() {
    }

    protected function getMethodProtected() {
    }

    private function getMethodPrivate() {
    }

    public static function getMethodPublicStatic() {
    }

    final public function getMethodPublicFinal() {
    }

    final public static function getMethodPublicFinalStatic() {
    }

    private static function getMethodPrivateFinalStatic() {
    }
}
