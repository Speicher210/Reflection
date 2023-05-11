<?php

namespace Wingu\OctopusCore\Reflection\Annotation\Tags;

use Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition;

/**
 * Base class for annotations tags.
 */
class BaseTag implements TagInterface
{

    /**
     * The annotation definition.
     *
     * @var \Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition
     */
    protected $definition;

    /**
     * The name of the tag.
     *
     * @var string
     */
    protected $tagName;

    /**
     * The description of the annotation tag.
     *
     * @var string
     */
    protected $description;

    /**
     * Constructor.
     *
     * @param \Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition $definition The annotation definition.
     */
    public function __construct(AnnotationDefinition $definition)
    {
        $this->definition = $definition;
        $this->initTag();
    }

    /**
     * Initialize the annotation tag.
     */
    protected function initTag(): void
    {
        $this->tagName = $this->definition->getTag();

        $description = trim(($this->definition->getDescription())??'');
        if ($description !== '') {
            $this->description = trim($this->definition->getDescription());
        }
    }

    /**
     * Get the tag name of the annotation tag.
     *
     * @return string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Get the description of the annotation tag.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
