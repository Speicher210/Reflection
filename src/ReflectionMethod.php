<?php

namespace Wingu\OctopusCore\Reflection;

use Wingu\OctopusCore\Reflection\Exceptions\RuntimeException;

/**
 * Reflection about a method.
 */
class ReflectionMethod extends \ReflectionMethod
{

    use ReflectionDocCommentTrait;

    /**
     * Gets declaring class.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionClass
     */
    public function getDeclaringClass(): ReflectionClass
    {
        return new ReflectionClass(parent::getDeclaringClass()->getName());
    }

    /**
     * Gets prototype.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionMethod
     */
    public function getPrototype(): ReflectionMethod
    {
        $prototype = parent::getPrototype();

        return new static($prototype->getDeclaringClass()->getName(), $prototype->getName());
    }

    /**
     * Get the body of the method.
     *
     * @return string
     * @throws \Wingu\OctopusCore\Reflection\Exceptions\RuntimeException If the method belongs to an internal class or is abstract.
     */
    public function getBody()
    {
        if ($this->isAbstract() === true) {
            throw new RuntimeException('Can not get body of an abstract method');
        }

        $fileName = $this->getDeclaringClass()->getFileName();
        if ($fileName === false) {
            throw new RuntimeException('Can not get body of a method belonging to an internal class.');
        }

        $lines = file($fileName, FILE_IGNORE_NEW_LINES);
        $lines = array_slice($lines, $this->getStartLine() - 1, ($this->getEndLine() - $this->getStartLine() + 1),
            true);
        $lines = implode("\n", $lines);

        $firstBracketPos = strpos($lines, '{');
        $lastBracketPost = strrpos($lines, '}');
        $body = substr($lines, $firstBracketPos + 1, $lastBracketPost - $firstBracketPos - 1);

        return trim(rtrim($body), "\n\r");
    }

    /**
     * Gets parameters.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionParameter[]
     */
    public function getParameters(): array
    {
        $function = array($this->getDeclaringClass()->getName(), $this->getName());
        $res = parent::getParameters();

        foreach ($res as $key => $val) {
            $res[$key] = new ReflectionParameter($function, $val->getName());
        }

        return $res;
    }

    /**
     * Gets extension info.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionExtension
     */
    public function getExtension(): ?ReflectionExtension
    {
        $extensionName =  $this->getExtensionName();
        if ($extensionName !== false) {
            return new ReflectionExtension($extensionName);
        } else {
            return null;
        }
    }
}
