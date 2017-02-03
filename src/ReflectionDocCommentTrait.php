<?php

namespace Wingu\OctopusCore\Reflection;

/**
 * Trait fot getting the documentation comment.
 */
trait ReflectionDocCommentTrait
{

    /**
     * The documentation comment object.
     *
     * @var \Wingu\OctopusCore\Reflection\ReflectionDocComment
     */
    protected $reflectionDocComment;

    /**
     * Get the document of the method.
     * 
     * @param string $trimLinePattern Pattern for trim() function applied to each line. Usefull to leave spaces or tabs. The default is the same as calling trim() without the argument.
     * @return \Wingu\OctopusCore\Reflection\ReflectionDocComment
     */
    public function getReflectionDocComment($trimLinePattern = " \t\n\r\0\x0B")
    {
        if ($this->reflectionDocComment === null) {
            $this->reflectionDocComment = new ReflectionDocComment((string)$this->getDocComment(), $trimLinePattern);
        }

        return $this->reflectionDocComment;
    }

    /**
     * Return the string containing the current constant comment or false if there's no comment.
     *
     * @return string|false
     */
    abstract public function getDocComment();
}
