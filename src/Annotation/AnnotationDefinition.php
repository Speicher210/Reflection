<?php

namespace Wingu\OctopusCore\Reflection\Annotation;

/**
 * Class that holds the raw output of the annotation parser for one annotation.
 */
class AnnotationDefinition
{

    /**
     * The tag name of the annotation.
     *
     * @var string
     */
    protected $tag;

    /**
     * The description text for the annotation.
     *
     * @var string
     */
    protected $description;

    /**
     * Constructor.
     *
     * @param string $tag The tag name for the annotation.
     * @param string $description The annotation description.
     */
    public function __construct($tag, $description = null)
    {
        $this->tag = (string)$tag;

        $description = trim((string)$description);
        if ($description !== '') {
            $this->description = $description;
        }
    }

    /**
     * Get the tag name.
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Get the description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
