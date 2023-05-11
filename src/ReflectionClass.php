<?php

namespace Wingu\OctopusCore\Reflection;

use Wingu\OctopusCore\Reflection\Exceptions\RuntimeException;

/**
 * Reflection about a class.
 */
class ReflectionClass extends \ReflectionClass
{

    use ReflectionDocCommentTrait;

    /**
     * Get the body of the class.
     *
     * @return string
     * @throws \Wingu\OctopusCore\Reflection\Exceptions\RuntimeException If the class is internal.
     */
    public function getBody()
    {
        $fileName = $this->getFileName();
        if ($fileName === false) {
            throw new RuntimeException('Can not get body of a class that is internal.');
        }

        $lines = file($fileName, FILE_IGNORE_NEW_LINES);
        $lines = array_slice(
            $lines,
            $this->getStartLine() - 1,
            ($this->getEndLine() - $this->getStartLine() + 1),
            true
        );
        $lines = implode("\n", $lines);

        $firstBracketPos = strpos($lines, '{');
        $lastBracketPost = strrpos($lines, '}');
        $body = substr($lines, $firstBracketPos + 1, $lastBracketPost - $firstBracketPos - 1);

        return trim(rtrim($body), "\n\r");
    }

    /**
     * Gets the interfaces.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionClass[]
     */
    public function getInterfaces(): array
    {
        $return = array();

        foreach ($this->getInterfaceNames() as $interface) {
            $return[$interface] = new static($interface);
        }

        return $return;
    }

    /**
     * Get interfaces that are implemented directly by the reflected class.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionClass[]
     */
    public function getOwnInterfaces()
    {
        $parent = $this->getParentClass();
        if ($parent !== false) {
            return array_diff_key($this->getInterfaces(), $parent->getInterfaces());
        } else {
            return $this->getInterfaces();
        }
    }

    /**
     * Gets a reflection on a method.
     *
     * @param string $name The method name.
     * @return \Wingu\OctopusCore\Reflection\ReflectionMethod
     */
    public function getMethod($name): ReflectionMethod
    {
        return new ReflectionMethod($this->getName(), $name);
    }

    /**
     * Gets a list of methods.
     *
     * @param integer $filter Filter for method types. This is an OR filter only.
     * @return \Wingu\OctopusCore\Reflection\ReflectionMethod[]
     */
    public function getMethods($filter = -1): array
    {
        $return = parent::getMethods($filter);
        foreach ($return as $key => $val) {
            $return[$key] = new ReflectionMethod($this->getName(), $val->getName());
        }

        return $return;
    }

    /**
     * Gets a list of own methods (not inherited).
     *
     * @param integer $filter Filter for method types.
     * @return \Wingu\OctopusCore\Reflection\ReflectionMethod[]
     */
    public function getOwnMethods($filter = -1)
    {
        $return = $this->getMethods($filter);

        $traitMethods = $traitAliases = array();
        foreach ($this->getTraits() as $trait) {
            $traitAliases = array_merge($traitAliases, $this->getTraitAliases());
            foreach ($trait->getMethods($filter) as $method) {
                $traitMethods[] = $method->getName();
            }
        }

        $traitMethods = array_merge($traitMethods, array_keys($traitAliases));

        foreach ($return as $key => $val) {
            if ($val->class === $this->getName() && in_array($val->getName(), $traitMethods) === false) {
                $return[$key] = new ReflectionMethod($this->getName(), $val->getName());
            } else {
                unset($return[$key]);
            }
        }

        return array_values($return);
    }

    /**
     * Gets the constructor of the class.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionMethod
     */
    public function getConstructor(): ?ReflectionMethod
    {
        if ($this->hasMethod('__construct') === true) {
            return $this->getMethod('__construct');
        } else {
            return null;
        }
    }

    /**
     * Checks if this method belongs to the current class (not inherited).
     *
     * @param string $name Name of the method being checked for.
     * @return boolean
     */
    public function hasOwnMethod($name)
    {
        if ($this->hasMethod($name) === true) {
            return $this->getMethod($name)->class === $this->getName();
        } else {
            return false;
        }
    }

    /**
     * Get the parent class.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionClass
     */
    #[\ReturnTypeWillChange]
    public function getParentClass()
    {
        $parent = parent::getParentClass();

        return ($parent !== false) ? new static($parent->getName()) : false;
    }

    /**
     * Get a property reflection.
     *
     * @param string $name Name of the property.
     * @return \Wingu\OctopusCore\Reflection\ReflectionProperty
     */
    public function getProperty($name): ReflectionProperty
    {
        return new ReflectionProperty($this->getName(), $name);
    }

    /**
     * Gets properties.
     *
     * @param integer $filter Filter for the properties.
     * @return \Wingu\OctopusCore\Reflection\ReflectionProperty[]
     */
    public function getProperties($filter = -1): array
    {
        $properties = parent::getProperties($filter);
        foreach ($properties as $key => $val) {
            $properties[$key] = new ReflectionProperty($this->getName(), $val->getName());
        }

        return $properties;
    }

    /**
     * Get properties that are defined in the reflected class.
     *
     * @param integer $filter Filter for the properties.
     * @return \Wingu\OctopusCore\Reflection\ReflectionProperty[]
     */
    public function getOwnProperties($filter = -1)
    {
        $return = $this->getProperties($filter);

        $traitProperties = array();
        foreach ($this->getTraits() as $trait) {
            foreach ($trait->getProperties($filter) as $property) {
                $traitProperties[] = $property->getName();
            }
        }

        foreach ($return as $key => $val) {
            if ($val->class === $this->getName() && in_array($val->getName(), $traitProperties) === false) {
                $return[$key] = new ReflectionProperty($this->getName(), $val->getName());
            } else {
                unset($return[$key]);
            }
        }

        return array_values($return);
    }

    /**
     * Get the constants that are defined in the class
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionConstant[] the array of constants
     */
    public function getConstants($filter = null): array
    {
        $constants = parent::getConstants();
        $returnConstants = array();
        foreach ($constants as $key => $value) {
            $returnConstants[$key] = new ReflectionConstant($this->getName(), $key);
        }

        return $returnConstants;
    }

    /**
     * Get constants that are defined directly by the reflected class.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionConstant[]
     */
    public function getOwnConstants()
    {
        if ($this->getParentClass() === false) {
            return $this->getConstants();
        } else {
            return array_diff_key($this->getConstants(), $this->getParentClass()->getConstants());
        }
    }

    /**
     * Returns an array of traits used by this class.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionClass[]
     */
    public function getTraits(): array
    {
        $return = parent::getTraits();
        if ($return !== null) {
            foreach ($return as $key => $val) {
                $return[$key] = new static($val->getName());
            }
        }

        return $return;
    }

    /**
     * Get the use statements.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionClassUse[]
     */
    public function getUses()
    {
        $return = array();
        foreach ($this->getTraitNames() as $traitName) {
            $return[] = new ReflectionClassUse($this->name, $traitName);
        }

        return $return;
    }

    /**
     * Gets a ReflectionExtension object for the extension which defined the class.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionExtension
     */
    public function getExtension(): ?ReflectionExtension
    {
        $extensionName = $this->getExtensionName();
        if ($extensionName !== false) {
            return new ReflectionExtension($extensionName);
        } else {
            return null;
        }
    }
}
