<?php

namespace Wingu\OctopusCore\Reflection\Tests;

/**
 * Base class for test cases.
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase {

    /**
     * This is a helper method to call a private/protected method on an object.
     *
     * @param Object $obj The object with the method.
     * @param string $methodName The name of the method.
     * @param array $args The arguments for the method.
     * @return mixed
     */
    public function callMethod($obj, $methodName, array $args = array()) {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }

    /**
     * This is a helper method to set the value of a private/protected property on an object.
     *
     * @param Object $obj The object with the property.
     * @param string $property The name of the property to set.
     * @param mixed $value The value to set.
     */
    public function setProperty($obj, $property, $value) {
        $class = new \ReflectionClass($obj);
        $property = $class->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($obj, $value);
    }

    /**
     * This is a helper method to get the value of a private/protected property on an object.
     *
     * @param Object $obj The object with the property.
     * @param string $property The name of the property to get.
     * @return mixed
     */
    public function getProperty($obj, $property) {
    	$class = new \ReflectionClass($obj);
    	$property = $class->getProperty($property);
    	$property->setAccessible(true);
    	return $property->getValue($obj);
    }

}