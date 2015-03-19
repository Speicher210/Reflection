<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures;

abstract class AbstractClass {

    public $publicProperty;

    protected $protectedProperty;

    private $privateProperty;

    abstract public function getMethodAbstract();
}