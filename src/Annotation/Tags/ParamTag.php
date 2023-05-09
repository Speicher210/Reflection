<?php

namespace Wingu\OctopusCore\Reflection\Annotation\Tags;

use Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition;
use Wingu\OctopusCore\Reflection\Annotation\Exceptions\InvalidArgumentException;
use Wingu\OctopusCore\Reflection\Annotation\Exceptions\RuntimeException;

/**
 * Annotation for "@param" annotation tag.
 */
class ParamTag extends BaseTag
{

    /**
     * The parameter type.
     *
     * @var string
     */
    protected $paramType;

    /**
     * The name of the parameter.
     *
     * @var string
     */
    protected $paramName;

    /**
     * The description of the parameter.
     *
     * @var string
     */
    protected $paramDescription;

    /**
     * Constructor.
     *
     * @param \Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition $definition The annotation definition.
     * @throws \Wingu\OctopusCore\Reflection\Annotation\Exceptions\InvalidArgumentException If the definition is not valid.
     */
    public function __construct(AnnotationDefinition $definition)
    {
        if ($definition->getTag() !== 'param') {
            throw new InvalidArgumentException('The definition tag must be "param".');
        }

        parent::__construct($definition);
    }

    /**
     * Initialize the annotation tag.
     */
    protected function initTag(): void
    {
        parent::initTag();

        $value = preg_split('/[\s]+/', $this->description??'', 3);

        if (isset($value[0]) === true && trim($value[0]) !== '') {
            $this->paramType = trim($value[0]);
        }

        if (isset($value[1]) === true && trim($value[1]) !== '') {
            $this->paramName = trim($value[1]);
            if (strpos($this->paramName, '$') !== 0) {
                throw new RuntimeException('The name of the parameter does not start with "$".');
            }
        }
        if (isset($value[2]) === true && trim($value[2]) !== '') {
            $this->paramDescription = trim($value[2]);
        }
    }

    /**
     * Get the type of the parameter.
     *
     * @return string
     */
    public function getParamType()
    {
        return $this->paramType;
    }

    /**
     * Get the parameter name.
     *
     * @return string
     */
    public function getParamName()
    {
        return $this->paramName;
    }

    /**
     * Get the parameter description.
     *
     * @return string
     */
    public function getParamDescription()
    {
        return $this->paramDescription;
    }
}
