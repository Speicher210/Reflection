<?php

namespace Wingu\OctopusCore\Reflection;

/**
 * Reflection about a class constant.
 */
class ReflectionConstant implements \Reflector
{

    use ReflectionDocCommentTrait;

    /**
     * The name of the constant.
     *
     * @var string
     */
    protected $name;

    /**
     * The value of the constant.
     *
     * @var mixed
     */
    protected $value;

    /**
     * The class where the constant has been declared.
     *
     * @var \Wingu\OctopusCore\Reflection\ReflectionClass
     */
    protected $declaringClass;

    /**
     * The class where the constant was reflected from (the one passed to the consturctor)
     *
     * @var \Wingu\OctopusCore\Reflection\ReflectionClass
     */
    protected $reflectedClass;
    
    /**
     * Constructor.
     *
     * @param string $class The name of the class where the constant has been declared.
     * @param string $name The name of the constant to reflect.
     */
    public function __construct($class, $name)
    {
        $this->reflectedClass = new ReflectionClass($class);
        $this->name = $name;
        $this->value = $this->reflectedClass->getConstant($name);
    }

    /**
     * Get a reflection of the class where the constant has been declared.
     *
     * @return \Wingu\OctopusCore\Reflection\ReflectionClass
     */
    public function getDeclaringClass()
    {
        if ($this->declaringClass === null) {
            $reflectionClass = $this->reflectedClass;
            $name = $this->getName();
            while ($reflectionClass) {
                foreach ($reflectionClass->getOwnConstants() as $ownConstant) {
                    if ($ownConstant->getName() === $name) {
                        break 2;
                    }
                }
                $reflectionClass = $reflectionClass->getParentClass();
            }
            $this->declaringClass = $reflectionClass ? $reflectionClass : $this->reflectedClass;
        }
        return $this->declaringClass;
    }

    /**
     * Get the value of the constant.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the name of the constant.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Export the current constant reflection.
     *
     * @param string $className The class name where the constant is defined.
     * @param string $constantName The name of the constant.
     * @param boolean $return Flag if the export should be returned or not.
     * @return string
     */
    public static function export($className, $constantName, $return = false)
    {
        $export = new self($className, $constantName);
        $export = (string)$export;
        if ($return === true) {
            return $export;
        } else {
            echo $export;

            return null;
        }
    }

    /**
     * Return the string representation of the ReflectionConstant object.
     *
     * @return string
     */
    public function __toString()
    {
        return 'Constant [ ' . gettype($this->value) . ' const ' . $this->getName() . ' ] { ' . $this->value . ' }';
    }

    /**
     * Return the string containing the current constant comment or false if there's no comment.
     *
     * @return string
     */
    public function getDocComment()
    {
        $fileName = $this->getDeclaringClass()->getFileName();
        if ($fileName === false) {
            return false;
        } else {
            return $this->getDocCommentFromFile($fileName);
        }
    }

    /**
     * Returns the doc comment from an existing filename or false if empty.
     *
     * @param string $fileName an existing filename
     * @return string
     */
    private function getDocCommentFromFile($fileName)
    {
        $lines = file($fileName, FILE_IGNORE_NEW_LINES);

        $declaringClassStartLine = $this->getDeclaringClass()->getStartLine() - 1;
        $declaringClassLength = $this->getDeclaringClass()->getEndLine() - $declaringClassStartLine + 1;

        $currentClassLines = array_slice($lines, $declaringClassStartLine, $declaringClassLength, true);

        // Need the php open tag to tokenize the class.
        $tokens = token_get_all("<?php\n" . implode("\n", $currentClassLines));

        $return = false;
        $constDeclarationKey = $this->getCurrentConstantKeyFromClassTokens($tokens);

        // Now we have the key value of the constant declaration, we have to pick up the comment before the declaration (if it exists).
        for ($constDeclarationKey--; $constDeclarationKey > 0; $constDeclarationKey--) {
            if ($tokens[$constDeclarationKey][0] !== T_WHITESPACE && $tokens[$constDeclarationKey][0] !== T_DOC_COMMENT) {
                break;
            } elseif ($tokens[$constDeclarationKey][0] === T_DOC_COMMENT) {
                $return = $tokens[$constDeclarationKey][1];
                break;
            }
        }

        return $return;
    }

    /**
     * Returns the current constant declaration key in the list of given tokens. Returns 0 if not found.
     *
     * @param array $tokens the array of tokens (see http://www.php.net/manual/en/ref.tokenizer.php)
     * @return int
     */
    private function getCurrentConstantKeyFromClassTokens($tokens)
    {
        $parsingStateConstDeclarationFound = false;
        $constDeclarationKey = 0;

        foreach ($tokens as $key => $token) {
            if (is_array($token) === true && $token[0] === T_CONST) {
                $parsingStateConstDeclarationFound = true;
                $constDeclarationKey = $key;
            }

            if ($parsingStateConstDeclarationFound === true && $token[0] === T_STRING) {
                if ($token[1] === $this->getName()) {
                    break;
                } else {
                    $parsingStateConstDeclarationFound = false;
                    $constDeclarationKey = 0;
                }
            }
        }

        return $constDeclarationKey;
    }
}
