<?php

namespace Wingu\OctopusCore\Reflection\Annotation;

/**
 * Annotations parser class.
 */
class Parser
{

    const SKIP = -1;

    const SCAN = 1;

    const NAME = 2;

    const COPY_LINE = 3;

    const COPY_ARRAY = 4;

    /**
     * The original comment before parsing it.
     *
     * @var string
     */
    protected $originalComment;

    /**
     * An array of found annotation definitions in the comment.
     *
     * @var \Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition[]
     */
    protected $foundAnnotationDefinitions = array();

    /**
     * Constructor.
     *
     * @param string $commentString The comment string to parse.
     * @throws \Wingu\OctopusCore\Reflection\Annotation\Exceptions\RuntimeException If the comment can not be parsed.
     */
    public function __construct($commentString)
    {
        $this->originalComment = $commentString;

        $commentString = trim(preg_replace('/^[\/\*\# \t]+/m', '', $commentString));
        $commentString = str_replace("\r\n", "\n", $commentString) . "\n";
        $commentStringLen = strlen($commentString);

        $state = self::SCAN;
        $nesting = 0;
        $matches = array();

        $name = '';
        $value = '';

        for ($i = 0; $i < $commentStringLen; $i++) {
            $character = $commentString[$i];

            switch ($state) {
                case self::SCAN:
                    if ($character === '@') {
                        $name = '';
                        $value = '';
                        $state = self::NAME;
                    } else {
                        if ($character !== "\n" && $character !== ' ' && $character !== "\t") {
                            $state = self::SKIP;
                        }
                    }
                    break;

                case self::SKIP:
                    if ($character === "\n") {
                        $state = self::SCAN;
                    }
                    break;

                case self::NAME:
                    $m = preg_match('/[a-zA-Z0-9\-\\\\]/', $character);
                    if ($m !== 0 && $m !== false) {
                        $name .= $character;
                    } else {
                        if ($character === ' ') {
                            $state = self::COPY_LINE;
                        } else {
                            if ($character === '(') {
                                $nesting++;
                                $value = $character;
                                $state = self::COPY_ARRAY;
                            } else {
                                if ($character === "\n") {
                                    $matches[$name][] = new AnnotationDefinition($name);
                                    $state = self::SCAN;
                                } else {
                                    $state = self::SKIP;
                                }
                            }
                        }
                    }
                    break;

                case self::COPY_LINE:
                    if ($character === "\n") {
                        $matches[$name][] = new AnnotationDefinition($name, $value);
                        $state = self::SCAN;
                    } else {
                        $value .= $character;
                    }
                    break;

                case self::COPY_ARRAY:
                    if ($character === '(') {
                        $nesting++;
                    }

                    if ($character === ')') {
                        $nesting--;
                    }

                    $value .= $character;

                    if ($nesting === 0) {
                        $matches[$name][] = new AnnotationDefinition($name, $value);
                        $state = self::SCAN;
                    }
                    break;
            }
        }

        if ($state !== self::SCAN) {
            throw new Exceptions\RuntimeException('The comment is not valid and can not be parsed.');
        }

        $this->foundAnnotationDefinitions = $matches;
    }

    /**
     * Get the definition of the annotations found int he comment.
     *
     * @return \Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition[]
     */
    public function getFoundAnnotationDefinitions()
    {
        return $this->foundAnnotationDefinitions;
    }
}
