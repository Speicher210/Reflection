<?php

namespace Wingu\OctopusCore\Reflection;

/**
 * Trait fot getting the documentation comment.
 */
trait ReflectionDocCommentTrait {

    /**
     * The documentation comment object.
     *
     * @var \Wingu\OctopusCore\Reflection\ReflectionDocComment
     */
    protected $reflectionDocComment;

    /**
     * Get the document of the method.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionDocComment
     */
    public function getReflectionDocComment() {
        if ($this->reflectionDocComment === null) {
            $this->reflectionDocComment = new ReflectionDocComment((string) $this->getDocComment());
        }

        return $this->reflectionDocComment;
    }
}