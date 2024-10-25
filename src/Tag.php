<?php

namespace Ht7\Html;

use Ht7\Html\Iterators\PreOrderIterator;
use Ht7\Html\Lists\AttributeList;
use Ht7\Html\Lists\NodeList;
use Ht7\Html\Models\SelfClosing;

/**
 * This class can build DOM like trees. To traverse them, there are two iterators:
 * - PostOrderIterator
 * - PreOrderIterator
 * Sadly atm only <code>PreOrderIterator</code> is here to be used.
 *
 * To build such a kind of DOM tree by an array, use:
 * <code>\Ht7\Html\Utilities\ImporterArray::read($array)</code>. To transform
 * a DOMElement-tree into an instance of the Tag class use the
 * <code>\Ht7\Html\Utilities\ImporterDom</code> class.
 *
 * @author Thomas Pluess
 */
class Tag extends Node implements \IteratorAggregate
{
    protected AttributeList $attributes;
    /**
     * Create an instance of the Tag class.
     *
     * @param   string  $tagName            The name of the current tag.
     * @param   mixed   $content            The content of the current Tag instance.
     * @param   array   $attributes         Indexed array of Attribute instances.
     */
    public function __construct(protected string $tagName = 'div', NodeList|array|string|float|int $content = [], AttributeList|array $attributes = [])
    {
        $this->setContent($content)
            ->setAttributes($attributes);
    }
    /**
     * Get a string representation of the current tag instance.
     *
     * @return  string                      This method returns a string like:
     *                                      <code><tagName [attributes]>content<&#47;tagName></code>
     */
    public function __toString()
    {
        $tagName = $this->getTagName();
        $attrStr = (string) $this->getAttributes();
        $attrStrSanitized = empty($attrStr) ? '' : ' ' . $attrStr;

        return "<{$tagName}{$attrStrSanitized}"
            . ($this->isSelfClosing() ? ' />' : ">{$this->getContent()}</{$tagName}>");
    }
    /**
     * Get the defined attributes of the current tag instance.
     *
     * @return  AttributeList           The attributes of the present tag.
     */
    public function getAttributes(): AttributeList
    {
        return $this->attributes;
    }
    /**
     * Get the content of the current HTML element.
     *
     * @return  NodeList                The content of the current HTML element.
     */
    public function getContent(): NodeList
    {
        return parent::getContent();
    }
    /**
     * Get an iterator instance to iterate the current Tag.
     *
     * This method is called by using the foreach loop over a Tag instance.
     *
     * @return  Iterator
     */
    public function getIterator(): \Traversable
    {
        return $this->getIteratorPreOrder();
    }
    /**
     * Get the tag name of the current element.
     *
     * @return  string          The tag name.
     */
    public function getTagName(): string
    {
        return $this->tagName;
    }
    /**
     * Get a tree iterator which goes first every tree up before searching the
     * next.
     */
//    public function getTreeIteratorHorizontal()
//    {
//
//    }
    /**
     * Get a tree iterator which searches first every sibling before going up to
     * the next level.
     */
    public function getIteratorPreOrder(): PreOrderIterator
    {
        return new PreOrderIterator($this);
    }
    /**
     * Whetever the current tag is self closing or not.
     */
    public function isSelfClosing(): bool
    {
        return SelfClosing::is($this->getTagName());
    }
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): mixed
    {
        return [
            'attributes' => $this->getAttributes(),
            'content' => $this->getContent(),
            'tag' => $this->getTagName()
        ];
    }
    /**
     * Set the attributes of the current HTML element.
     *
     * @param   AttributeList|array   $attributes         Indexed array of
     *                                      <code>\Ht7\Html\Attribute</code>
     *                                      instances or an instance of
     *                                      <code>AttributeList</code>.
     */
    public function setAttributes(AttributeList|array $attributes): static
    {
        $this->attributes = $attributes instanceof AttributeList ? $attributes : new AttributeList($attributes);

        return $this;
    }
    /**
     * Set the inner content of the current tag.
     *
     * This method will throw an exception if the current tag is self closing
     * and the content is not empty.
     * If the content is not an instance of the NodeList class, a new NodeList
     * will be created. In this case the input validation will be delegated to
     * the NodeList.
     *
     * @param   NodeList|array|string|float|int|bool     $content    The content of the current Tag
     *                                      instance. This must be a NodeList
     *                                      instance or an array.
     * @throws  \BadMethodCallException
     */
    // public function setContent(mixed $content): static
    public function setContent(NodeList|array|string|float|int|bool $content): static
    {
        if ($this->isSelfClosing() && !empty($content)) {
            $msg = 'This tag (%s) can not have content, because it is self'
                . ' closing.';
            $e = sprintf($msg, gettype($this->getTagName()));

            throw new \BadMethodCallException($e);
        }

        if (is_scalar($content) || $content instanceof Node) {
            $content = [$content];
        }

        $this->content = $content instanceof NodeList ? $content : new NodeList($content);

        return $this;
    }
    /**
     * Set the name of the current tag.
     *
     * @param   string      $name           The tag name of the current HTML element.
     */
    public function setTagName(string $name): static
    {
        $this->tagName = $name;

        return $this;
    }
}
