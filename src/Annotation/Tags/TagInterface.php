<?php

namespace Wingu\OctopusCore\Reflection\Annotation\Tags;

/**
 * Interface for annotations.
 */
interface TagInterface
{

    /**
     * Get the tag name of the annotation.
     *
     * @return string
     */
    public function getTagName();

    /**
     * Get the description of the annotation.
     *
     * @return string
     */
    public function getDescription();
}
