<?php

namespace Wingu\OctopusCore\Reflection\Annotation\Tags;

use Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition;
use Wingu\OctopusCore\Reflection\Annotation\Exceptions\InvalidArgumentException;

/**
 * Annotation for "@var" annotation tag.
 */
class VarTag extends BaseTag
{

    /**
     * Constructor.
     *
     * @param \Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition $definition The annotation definition.
     * @throws \Wingu\OctopusCore\Reflection\Annotation\Exceptions\InvalidArgumentException If the definition is not valid.
     */
    public function __construct(AnnotationDefinition $definition)
    {
        if ($definition->getTag() !== 'var') {
            throw new InvalidArgumentException('The definition tag must be "var".');
        }

        parent::__construct($definition);
    }

    /**
     * Get the variable type.
     *
     * @return string
     */
    public function getVarType()
    {
        return $this->description;
    }
}
