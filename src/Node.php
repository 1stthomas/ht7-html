<?php

namespace Ht7\Html;

use \Ht7\Html\Renderable;

/**
 * Base class.
 *
 * @author Thomas Plüss
 */
abstract class Node implements \JsonSerializable, Renderable
{

    /**
     * The content of the current Node.
     *
     * @var     mixed       The content of the current Node, which can be a
     *                      string, Text- or a Tag-instance.
     */
    protected $content;

    /**
     * Get the content of the current HTML element.
     *
     * @return  NodeList                The content of the current HTML element.
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the inner content of the current tag.
     *
     * This method will throw an exception if the current tag is self closing.
     *
     * @param   mixed       $content        The content of the current Node
     *                                      instance.
     * @throws  BadMethodCallException
     * @throws  InvalidArgumentException
     */
    abstract public function setContent($content);
}
