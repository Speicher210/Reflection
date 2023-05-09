<?php

namespace Wingu\OctopusCore\Reflection;

use Wingu\OctopusCore\Reflection\Exceptions\RuntimeException;

/**
 * The ReflectionFunction class reports information about a function.
 */
class ReflectionFunction extends \ReflectionFunction
{

    use ReflectionDocCommentTrait;

    /**
     * Get the body of the function.
     *
     * @return string
     * @throws \Wingu\OctopusCore\Reflection\Exceptions\RuntimeException If the function is internal.
     */
    public function getBody()
    {
        $fileName = $this->getFileName();
        if ($fileName === false) {
            throw new RuntimeException('Can not get body of a function that is internal.');
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
     * Gets a ReflectionExtension object for the extension which defined the function.
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

    /**
     * Gets parameters.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionParameter[]
     */
    public function getParameters(): array
    {
        $res = parent::getParameters();

        foreach ($res as $key => $val) {
            $res[$key] = new ReflectionParameter($this->getName(), $val->getName());
        }

        return $res;
    }
}
