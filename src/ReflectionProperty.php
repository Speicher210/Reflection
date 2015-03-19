<?php

namespace Wingu\OctopusCore\Reflection;

/**
 * Reflection about a property.
 */
class ReflectionProperty extends \ReflectionProperty
{

    use ReflectionDocCommentTrait;

    /**
     * Gets declaring class.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionClass
     */
    public function getDeclaringClass()
    {
        return new ReflectionClass(parent::getDeclaringClass()->getName());
    }

    /**
     * Get the default value of the property.
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->getDeclaringClass()->getDefaultProperties()[$this->name];
    }
}
