<?php

namespace Wingu\OctopusCore\Reflection;

use Wingu\OctopusCore\Reflection\Annotation\AnnotationsCollection;

/**
 * Reflection on a document block.
 */
class ReflectionDocComment
{

    /**
     * The original document block that was parsed.
     *
     * @var string
     */
    private $originalDocBlock = '';

    /**
     * The parsed short description.
     *
     * @var string
     */
    protected $shortDescription;

    /**
     * The parsed long description.
     *
     * @var string
     */
    protected $longDescription;

    /**
     * Constructor.
     *
     * @param string $comment The original document block comment.
     * @param string $trimLinePattern Pattern for trim() function applied to each line. Usefull to leave spaces or tabs. The default is the same as calling trim() without the argument.
     */
    public function __construct($comment, $trimLinePattern = " \t\n\r\0\x0B")
    {
        $this->originalDocBlock = trim((string)$comment);

        $comment = preg_replace('#^\s*\*\s?#ms', '', trim($this->originalDocBlock, '/*'));
        $comment = preg_split('#^\s*(?=@[_a-zA-Z\x7F-\xFF][_a-zA-Z0-9\x7F-\xFF-]*)#m', $comment, 2);

        if (isset($comment[0]) === true) {
            $description = $comment[0];
            $description = preg_split("/\n|\n\r/", $description);
            array_walk($description, function (& $value, $key, $trimLinePattern) {
                $value = trim($value, $trimLinePattern);
            }, $trimLinePattern);

            foreach ($description as $key => $descLine) {
                if ($descLine !== '') {
                    $this->shortDescription = $descLine;
                    unset($description[$key]);
                    $this->longDescription = trim(implode("\n", $description));
                    $this->longDescription = $this->longDescription !== '' ? $this->longDescription : null;
                    break;
                }

                unset($description[$key]);
            }
        }
    }

    /**
     * Get the full description.
     * The long description (if present) will be concatenated to the short description with an empty line between.
     *
     * @return string
     */
    public function getFullDescription()
    {
        if ($this->longDescription !== null) {
            return $this->shortDescription . "\n\n" . $this->longDescription;
        } else {
            return $this->shortDescription;
        }
    }

    /**
     * Get the short description.
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Get the long description.
     *
     * @return string
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }

    /**
     * Get the annotations collection.
     *
     * @return \Wingu\OctopusCore\Reflection\Annotation\AnnotationsCollection
     */
    public function getAnnotationsCollection()
    {
        return new AnnotationsCollection($this->originalDocBlock);
    }

    /**
     * Get the original doc block.
     *
     * @return string
     */
    public function getOriginalDocBlock()
    {
        return $this->originalDocBlock;
    }

    /**
     * Check if the documentation comment is empty or not.
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->originalDocBlock === '';
    }

    /**
     * Magic method to print out the document.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->originalDocBlock;
    }
}
