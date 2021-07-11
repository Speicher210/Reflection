<?php

namespace Wingu\OctopusCore\Reflection;

/**
 * Reflection of a PHP file.
 */
class ReflectionFile
{

    /**
     * The file that is reflected.
     *
     * @var string
     */
    protected $reflectedFilePath;

    /**
     * The reflected file content.
     *
     * @var string
     */
    protected $reflectedFileContent;

    /**
     * The namespaces in the file.
     *
     * @var array
     */
    protected $namespaces = array();

    /**
     * The use statements in the file.
     *
     * @var array
     */
    protected $uses = array();

    /**
     * Array of objects found in the file.
     *
     * @var \Reflector[]
     */
    protected $objects = array();

    /**
     * Constructor.
     *
     * @param string $reflectedFilePath The file path to reflect.
     */
    public function __construct($reflectedFilePath)
    {
        $realPath = realpath($reflectedFilePath);
        if ($realPath === false) {
            throw new \RuntimeException('File "' . $realPath . '" does not exist or is not valid.');
        }

        $this->reflectedFilePath = $realPath;
        $this->reflectedFileContent = file_get_contents($this->reflectedFilePath);

        $this->reflect();
    }

    /**
     * Do the reflection to gather data.
     */
    private function reflect()
    {
        $tokens = token_get_all($this->reflectedFileContent);
        $namespace = null;
        $classLevel = 0;
        $level = 0;
        $res = $uses = array();
        while ($token = current($tokens)) {
            next($tokens);
            switch (is_array($token) ? $token[0] : $token) {
                case T_CLASS:
                case T_INTERFACE:
                case T_TRAIT:
                    if ($name = $this->fetch($tokens, array(T_STRING))) {
                        $classLevel = $level + 1;
                        if ($namespace !== null) {
                            $objectFQN = $namespace . '\\' . $name;
                        } else {
                            $objectFQN = $name;
                        }
                        $this->objects[] = new ReflectionClass($objectFQN);
                    }
                    break;
                case T_NAMESPACE:
                    $tokenTypes = array(T_STRING, T_NS_SEPARATOR);
                    if (defined('T_NAME_QUALIFIED')) {
                        $tokenTypes[] = T_NAME_QUALIFIED;
                    }
                    if (defined('T_NAME_FULLY_QUALIFIED')) {
                        $tokenTypes[] = T_NAME_FULLY_QUALIFIED;
                    }
                    $namespace = '\\' . ltrim($this->fetch($tokens, $tokenTypes), '\\');
                    $res[$namespace] = array();
                    $uses = array();
                    break;
                case T_USE:
                    if ($classLevel === 0) {
                        while ($name = $this->fetch($tokens, array(T_STRING, T_NS_SEPARATOR))) {
                            $name = '\\' . ltrim($name, '\\');
                            if ($this->fetch($tokens, array(T_AS))) {
                                $uses[$this->fetch($tokens, array(T_STRING))] = $name;
                            } else {
                                $uses[$name] = $name;
                            }
                            if (!$this->fetch($tokens, array(','))) {
                                break;
                            }
                        }
                        $res[$namespace] = $uses;
                    }
                    break;
                case T_CURLY_OPEN:
                case T_DOLLAR_OPEN_CURLY_BRACES:
                case '{':
                    $level++;
                    break;
                case '}':
                    if ($level === $classLevel) {
                        $classLevel = 0;
                    }
                    $level--;
                    break;
            }
        }

        $this->namespaces = array_keys($res);
        $this->uses = $res;
    }

    /**
     * Get the token value for the next token of type.
     *
     * @param array $tokens The tokens array.
     * @param array $take The tokens to look for.
     * @return null|string
     */
    private function fetch(& $tokens, array $take)
    {
        $res = null;
        while ($token = current($tokens)) {
            list($token, $s) = is_array($token) ? $token : array($token, $token);
            if (in_array($token, $take, true)) {
                $res .= $s;
            } elseif (!in_array($token, array(T_DOC_COMMENT, T_WHITESPACE, T_COMMENT), true)) {
                break;
            }
            next($tokens);
        }

        return $res;
    }

    /**
     * Get the file path of the reflected file.
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->reflectedFilePath;
    }

    /**
     * Get the namespaces defined in the file.
     *
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * Get the use statements in the file.
     *
     * @return array
     */
    public function getUses()
    {
        return $this->uses;
    }

    /**
     * Get the objects found in the file.
     *
     * @return \Reflector[]
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * Resolve an alias for an FQN.
     *
     * @param string $fqn The fully qualified name to get the alias for.
     * @return string
     */
    public function resolveFqnToAlias($fqn)
    {
        foreach ($this->getUses() as $namespace => $uses) {
            $alias = array_search($fqn, $uses, true);
            if ($alias) {
                $parts = explode('\\', $alias);

                return end($parts);
            }
        }

        return $fqn;
    }
}
