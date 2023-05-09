<?php

namespace Wingu\OctopusCore\Reflection;

/**
 * Reflection about a parameter.
 */
class ReflectionParameter extends \ReflectionParameter
{

    /**
     * Parameter function.
     *
     * @var mixed
     */
    private $function;

    /**
     * Constructor.
     *
     * @param string $function The function to reflect parameters from. It can be also be in the format: array('className', 'methodName').
     * @param string $parameter The parameter name.
     */
    public function __construct($function, $parameter)
    {
        $this->function = $function;
        parent::__construct($function, $parameter);
    }

    /**
     * Gets a class.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionClass
     */
    public function getClass(): ?ReflectionClass
    {
        $class = null;
        if (PHP_VERSION_ID < 80000) {
            $class = parent::getClass();
        } else {
            $type = parent::getType();
            if ($type instanceof \ReflectionNamedType && class_exists($type->getName())) {
                $class = $type;
            }
        }
        if ($class !== null) {
            $class = new ReflectionClass($class->getName());
        }

        return $class;
    }

    /**
     * Gets the declaring class.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionClass
     */
    public function getDeclaringClass(): ?ReflectionClass
    {
        $class = parent::getDeclaringClass();
        if ($class !== null) {
            $class = new ReflectionClass($class->getName());
        }

        return $class;
    }

    /**
     * Gets the declaring function.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionMethod|\Wingu\OctopusCore\Reflection\ReflectionFunction
     */
    public function getDeclaringFunction(): \ReflectionFunctionAbstract
    {
        if (is_array($this->function) === true) {
            return new ReflectionMethod($this->function[0], $this->function[1]);
        } else {
            return new ReflectionFunction($this->function);
        }
    }
}
