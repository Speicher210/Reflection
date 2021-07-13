<?php

namespace Wingu\OctopusCore\Reflection;

use Wingu\OctopusCore\Reflection\Exceptions\InvalidArgumentException;

/**
 * Reflection about a class use statement.
 */
class ReflectionClassUse implements \Reflector
{

    /**
     * The name of the trait.
     *
     * @var string
     */
    protected $name;

    /**
     * The conflict resolutions of the trait.
     *
     * @var array
     */
    private $conflictResolutions = array();

    /**
     * The class where the use statement has been declared.
     *
     * @var \Wingu\OctopusCore\Reflection\ReflectionClass
     */
    protected $declaringClass;

    /**
     * The tokens list of the parsed PHP code.
     *
     * @var array
     */
    private $tokens = array();

    /**
     * The number of tokens found in the code.
     *
     * @var integer
     */
    private $tokensCount = 0;

    /**
     * The current token position.
     *
     * @var integer
     */
    private $tokenPos = 0;

    /**
     * Constructor.
     *
     * @param string $class The class name where the use statement is defined.
     * @param string $name The name of the trait.
     */
    public function __construct($class, $name)
    {
        $this->declaringClass = new ReflectionClass($class);
        $this->name = $name;

        $this->findConflictResolutions();
        $this->tokens = array();
        $this->tokensCount = $this->tokenPos = 0;
    }

    /**
     * Get the name of the trait.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the conflict resolutions.
     *
     * @return array
     */
    public function getConflictResolutions()
    {
        return $this->conflictResolutions;
    }

    /**
     * Find the conflict resolutions of a trait.
     *
     * @throws \Wingu\OctopusCore\Reflection\Annotation\Exceptions\InvalidArgumentException If the trait is not found.
     */
    protected function findConflictResolutions()
    {
        $contents = '<?php' . PHP_EOL . $this->declaringClass->getBody();
        $this->tokens = token_get_all($contents);
        $this->tokensCount = count($this->tokens);

        while (($token = $this->next()) !== null) {
            if ($token[0] === T_USE) {
                $conflicts = $this->extractConflictsFromUseStatement();
                if ($conflicts !== null) {
                    $this->conflictResolutions = explode(';', implode('', $conflicts));
                    $this->conflictResolutions = array_map('trim', $this->conflictResolutions);
                    $this->conflictResolutions = array_filter($this->conflictResolutions);

                    return;
                }
            }
        }

        throw new InvalidArgumentException('Could not find the trait "' . $this->name . '".');
    }

    /**
     * Extract the conflicts from the current use statement.
     *
     * @return array
     */
    private function extractConflictsFromUseStatement()
    {
        $class = '';
        $conflicts = array();
        $inConflicts = false;
        while (($token = $this->next($inConflicts)) !== null) {
            if ($token !== '}' && $inConflicts === true) {
                if (is_string($token) === true) {
                    $conflicts[] = trim($token);
                } else {
                    $conflicts[] = $token[1];
                }

                continue;
            }
            $tokenTypes = array(T_STRING, T_NS_SEPARATOR);
            if (defined('T_NAME_QUALIFIED')) {
                $tokenTypes[] = T_NAME_QUALIFIED;
            }
            if (defined('T_NAME_FULLY_QUALIFIED')) {
                $tokenTypes[] = T_NAME_FULLY_QUALIFIED;
            }
            if (in_array($token[0], $tokenTypes, true)) {
                $class .= $token[1];
            } else {
                if ($token === ',') {
                    if ($this->isSearchedTrait($class) === true) {
                        return $conflicts;
                    }

                    $class = '';
                } else {
                    if ($token === ';') {
                        if ($this->isSearchedTrait($class) === true) {
                            return $conflicts;
                        } else {
                            return null;
                        }
                    } else {
                        if ($token === '{') {
                            $inConflicts = true;
                        } else {
                            if ($token === '}') {
                                if ($this->isSearchedTrait($class) === true) {
                                    return $conflicts;
                                } else {
                                    return null;
                                }
                            } else {
                                break;
                            }
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Check if the found trait name is the one that we need to reflect.
     *
     * @param string $name The name of the trait found.
     * @return boolean
     */
    private function isSearchedTrait($name)
    {
        if ($this->name === $name) {
            return true;
        }

        if (strpos($name, '\\') === 0) {
            return $this->name === $name || '\\' . $this->name === $name;
        }

        $name = $this->declaringClass->getNamespaceName() . '\\' . $name;

        return $this->name === $name || $this->name === '\\' . $name;
    }

    /**
     * Return the next token that is not a whitespace or comment.
     *
     * @param boolean $includeWhiteSpace Flag if the whitespace should also be returned.
     * @return mixed
     */
    private function next($includeWhiteSpace = false)
    {
        for ($i = $this->tokenPos; $i < $this->tokensCount; $i++) {
            $this->tokenPos++;
            if (($includeWhiteSpace === false && $this->tokens[$i][0] === T_WHITESPACE) || $this->tokens[$i][0] === T_COMMENT) {
                continue;
            }

            return $this->tokens[$i];
        }

        return null;
    }

    /**
     * Export the current use statement reflection.
     *
     * @param string $className The class name where the use statement is defined.
     * @param string $name The name of the trait.
     * @param boolean $return Flag if the export should be returned or not.
     * @return string
     */
    public static function export($className, $name, $return = false)
    {
        $export = new self($className, $name);
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
        $return = 'ClassUse [ trait ' . $this->name . ' ]';
        if (count($this->conflictResolutions) > 0) {
            return $return . ' {' . PHP_EOL . implode(';' . PHP_EOL, $this->conflictResolutions) . ';' . PHP_EOL . '}';
        } else {
            return $return . ' { }';
        }
    }
}
