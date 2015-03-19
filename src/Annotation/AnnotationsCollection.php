<?php

namespace Wingu\OctopusCore\Reflection\Annotation;

/**
 * A collection of parsed types.
 */
class AnnotationsCollection
{

    /**
     * The parsed annotations definitions.
     *
     * @var \Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition[]
     */
    protected $annotationsDefinitions = array();

    /**
     * The annotation tags mapper.
     *
     * @var \Wingu\OctopusCore\Reflection\Annotation\TagMapper
     */
    protected $tagsMapper;

    /**
     * Constructor.
     *
     * @param string $comment The comment string from which to extract the annotations.
     */
    public function __construct($comment)
    {
        $parser = new Parser($comment);
        $this->annotationsDefinitions = $parser->getFoundAnnotationDefinitions();
    }

    /**
     * Set the annotation tag mapper.
     *
     * @param \Wingu\OctopusCore\Reflection\Annotation\TagMapper $tm The tag mapper to set.
     * @return \Wingu\OctopusCore\Reflection\Annotation\AnnotationsCollection
     */
    public function setTagMapper(TagMapper $tm)
    {
        $this->tagsMapper = $tm;

        return $this;
    }

    /**
     * Get the annotation mapper.
     *
     * @return \Wingu\OctopusCore\Reflection\Annotation\TagMapper
     */
    public function getTagMapper()
    {
        return $this->tagsMapper;
    }

    /**
     * Check if the collection contains an annotation tag.
     *
     * @param string $tag The annotation tag name to check.
     * @return boolean
     */
    public function hasAnnotationTag($tag)
    {
        return isset($this->annotationsDefinitions[$tag]);
    }

    /**
     * Get all annotations.
     *
     * @return \Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface[]
     */
    public function getAnnotations()
    {
        $result = array();
        foreach ($this->annotationsDefinitions as $annotationDefinition) {
            $result = array_merge($result, $this->buildAnnotationsInstances($annotationDefinition));
        }

        return $result;
    }

    /**
     * Get an annotation.
     *
     * @param string $tag The annotation tag name.
     * @return \Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface[]
     * @throws \Wingu\OctopusCore\Reflection\Annotation\Exceptions\OutOfBoundsException If there are no annotations with the specified tag.
     */
    public function getAnnotation($tag)
    {
        if ($this->hasAnnotationTag($tag) === true) {
            $result = array();
            foreach ($this->annotationsDefinitions[$tag] as $annotationDefinition) {
                $result[] = $this->buildAnnotationInstance($annotationDefinition);
            }

            return $result;
        } else {
            throw new Exceptions\OutOfBoundsException('No annotations with the tag "' . $tag . '" were found.');
        }
    }

    /**
     * Get the annotation class to use for an annotation tag.
     *
     * @param string $tag The annotation tag name.
     * @return string
     */
    protected function getAnnotationClass($tag)
    {
        if ($this->tagsMapper !== null && $this->tagsMapper->hasMappedTag($tag) === true) {
            return $this->tagsMapper->getMappedTag($tag);
        } elseif (class_exists(__NAMESPACE__ . '\Tags\\' . ucfirst($tag) . 'Tag') === true) {
            return __NAMESPACE__ . '\Tags\\' . ucfirst($tag) . 'Tag';
        } else {
            return __NAMESPACE__ . '\Tags\BaseTag';
        }
    }

    /**
     * Build all the annotation instances for an annotation tag.
     *
     * @param \Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition[] $annotationDefinitions The annotation tag name.
     * @return \Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface[]
     */
    protected function buildAnnotationsInstances(array $annotationDefinitions)
    {
        $result = array();
        foreach ($annotationDefinitions as $annotationDefinition) {
            $result[] = $this->buildAnnotationInstance($annotationDefinition);
        }

        return $result;
    }

    /**
     * Build an annotation.
     *
     * @param \Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition $annotationDefinition The annotation definition.
     * @return \Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface
     */
    protected function buildAnnotationInstance(AnnotationDefinition $annotationDefinition)
    {
        $class = $this->getAnnotationClass($annotationDefinition->getTag());
        $annotation = new $class($annotationDefinition);

        return $annotation;
    }
}
