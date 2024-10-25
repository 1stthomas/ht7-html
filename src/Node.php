<?php

namespace Ht7\Html;

use Ht7\Html\Renderable;
use Ht7\Html\Lists\NodeList;

/**
 * Base class.
 *
 * @author Thomas Plüss
 */
abstract class Node implements \JsonSerializable, Renderable
{

    protected NodeList|string $content;

    /**
     * Get the content of the present HTML element.
     */
    public function getContent(): NodeList|string
    {
        return $this->content;
    }

    /**
     * Set the inner content of the present HTML element.
     *
     * This method will throw an exception if the present tag is self closing.
     *
     * @param   NodeList|array|string|float|int|bool     $content    The content of the current Node
     *                                      instance.
     * @throws  BadMethodCallException
     * @throws  InvalidArgumentException
     */
    abstract public function setContent(NodeList|array|string|float|int|bool $content): static;
}
