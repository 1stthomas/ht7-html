<?php

namespace Ht7\Html;

use Ht7\Html\Lists\NodeList;

/**
 * This is a simple text node.
 */
class Text extends Node
{
    /**
     * Create an instance of the text element.
     *
     * @param   string  $text           The content.
     */
    public function __construct(string $text)
    {
        $this->setContent($text);
    }
    /**
     * Get a string representation of the current class.
     *
     * @return  string                  The content.
     */
    public function __toString()
    {
        return $this->getContent();
    }
    /**
     * Get the content.
     *
     * @return  string                  The content of the current text element.
     */
    public function getContent(): NodeList|string
    {
        return parent::getContent();
    }
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): mixed
    {
        return $this->getContent();
    }
    /**
     * Set the content.
     *
     * Only scalar types will be accepted.
     *
     * @param   NodeList|array|string|float|int|bool   $text               The content as string, integer, float or bool.
     * @throws  \InvalidArgumentException
     */
    public function setContent(NodeList|array|string|float|int|bool $text): static
    {
        if (is_scalar($text)) {
            $this->content = (string) $text;
        } else {
            $e = 'The text content must be a scalar, found ' . gettype($text);

            throw new \InvalidArgumentException($e);
        }

        return $this;
    }
}
