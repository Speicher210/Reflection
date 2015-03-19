<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures;

trait TestTrait {

    use TestTrait2 {
    	TestTrait2::trait2Function2 as tf2;
    }

    public $publicTraitProperty;

    protected $protectedTraitProperty;

    private $privateTraitProperty;

    public function traitFunction1(){}
}

trait TestTrait2 {

    public $myTraitProperty;

    protected $myTraitProperty2;

	public function trait2Function1(){}
	public function trait2Function2(){}
}