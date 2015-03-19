<?php

namespace Wingu\OctopusCore\Reflection\Annotation;

use Wingu\OctopusCore\Reflection\Annotation\Exceptions\InvalidArgumentException;
use Wingu\OctopusCore\Reflection\Annotation\Exceptions\OutOfBoundsException;

/**
 * A mapper to hold the relation between the annotation tag name and the class used to represent it.
 */
class TagMapper
{

    /**
     * An array of classes mapped to the annotations tags.
     *
     * @var array
     */
    protected $mappedTags = array();

    /**
     * Map an annotation tag to a class.
     *
     * @param string $tag The name of the annotation tag.
     * @param string $class The class name to handle the annotation. It must implement \Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface.
     * @return \Wingu\OctopusCore\Reflection\Annotation\TagMapper
     * @throws \Wingu\OctopusCore\Reflection\Annotation\Exceptions\InvalidArgumentException If tag is invalid or class doesn't implement TagInterface.
     */
    public function mapTag($tag, $class)
    {
        if (preg_match('/^[A-Za-z0-9]+$/', $tag) <= 0) {
            throw new InvalidArgumentException('The name of the tag annotation is invalid.');
        }

        if (in_array('Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface', class_implements($class)) === false) {
            throw new InvalidArgumentException('The class "' . $class . '" must implement "' . __NAMESPACE__ . '\Tags\TagInterface".');
        }

        $this->mappedTags[$tag] = $class;

        return $this;
    }

    /**
     * Check if an annotation tag is mapped.
     *
     * @param string $tag The name of the annotation tag.
     * @return boolean
     */
    public function hasMappedTag($tag)
    {
        return isset($this->mappedTags[$tag]);
    }

    /**
     * Get the mapped annotations tags.
     *
     * @return array
     */
    public function getMappedTags()
    {
        return $this->mappedTags;
    }

    /**
     * Get the class to handle a specific annotation tag.
     *
     * @param string $tag The name of the annotation tag.
     * @return string
     * @throws \Wingu\OctopusCore\Reflection\Annotation\Exceptions\OutOfBoundsException If the annotation tag was not mapped.
     */
    public function getMappedTag($tag)
    {
        if (isset($this->mappedTags[$tag]) === true) {
            return $this->mappedTags[$tag];
        } else {
            throw new OutOfBoundsException('Annotation tag "' . $tag . '" was not mapped.');
        }
    }

    /**
     * Merge an annotation tag mapper into the current one.
     *
     * @param \Wingu\OctopusCore\Reflection\Annotation\TagMapper $tagMapper The annotations tag mapper to merge.
     * @param boolean $overwrite Flag if the existing found annotations tags should be overwritten.
     * @return \Wingu\OctopusCore\Reflection\Annotation\TagMapper
     */
    public function mergeTagMapper(TagMapper $tagMapper, $overwrite = true)
    {
        if ($overwrite === true) {
            $this->mappedTags = array_merge($this->mappedTags, $tagMapper->getMappedTags());
        } else {
            $this->mappedTags = array_merge($tagMapper->getMappedTags(), $this->mappedTags);
        }

        return $this;
    }
}
