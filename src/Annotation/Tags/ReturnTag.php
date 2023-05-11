<?php

namespace Wingu\OctopusCore\Reflection\Annotation\Tags;

use Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition;
use Wingu\OctopusCore\Reflection\Annotation\Exceptions\InvalidArgumentException;

/**
 * Annotation for "@return" annotation tag.
 */
class ReturnTag extends BaseTag
{

    /**
     * The return type.
     *
     * @var string
     */
    protected $returnType;

    /**
     * The return description.
     *
     * @var string
     */
    protected $returnDescription;

    /**
     * Constructor.
     *
     * @param \Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition $definition The annotation definition.
     * @throws \Wingu\OctopusCore\Reflection\Annotation\Exceptions\InvalidArgumentException If the definition is not valid.
     */
    public function __construct(AnnotationDefinition $definition)
    {
        if ($definition->getTag() !== 'return') {
            throw new InvalidArgumentException('The definition tag must be "return".');
        }

        parent::__construct($definition);
    }

    /**
     * Initialize the annotation tag.
     */
    protected function initTag(): void
    {
        parent::initTag();

        $value = preg_split('/[\s]+/', $this->description??'', 2);
        if (isset($value[0]) === true && trim($value[0]) !== '') {
            $this->returnType = $value[0];
        }

        if (isset($value[1]) === true && trim($value[1]) !== '') {
            $this->returnDescription = trim($value[1]);
        }
    }

    /**
     * Get the return type.
     *
     * @return string
     */
    public function getReturnType()
    {
        return $this->returnType;
    }

    /**
     * Get the return description.
     *
     * @return string
     */
    public function getReturnDescription()
    {
        return $this->returnDescription;
    }
}
