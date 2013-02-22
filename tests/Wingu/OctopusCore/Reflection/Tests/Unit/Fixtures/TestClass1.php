<?php

namespace Wingu\OctopusCore\Reflection\Tests\Unit\Fixtures;

class TestClass1 {

    const CONSTANT1 = 'C1';

    public $publicInteger1 = -1;

    public $publicInteger2 = 0;

    public $publicInteger3 = 1;

    public $publicString = 'string';

    public $publicArray = array(1, 'test');

    public $publicNull1;

    public $publicNull2 = null;

    public static $publicStaticInteger = 1;

    public static $publicStaticString = 'staticstring';

    public static $publicStaticArray = [0, 'static'];

    public static $publicStaticNull1;

    public static $publicStaticNull2 = null;

    public function __construct() {
    }
}