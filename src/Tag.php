<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ht7\Html;

use \BadMethodCallException;
use \InvalidArgumentException;
use \IteratorAggregate;
use \Ht7\Base\Exceptions\InvalidDatatypeException;
use \Ht7\Html\Iterators\PreOrderIterator;
use \Ht7\Html\Lists\AttributeList;
use \Ht7\Html\Lists\NodeList;
use \Ht7\Html\Models\SelfClosing;

/**
 * This class can build DOM like trees. To traverse them, there are two iterators:
 * - PostOrderIterator
 * - PreOrderIterator
 *
 * To build such a kind of DOM tree by an array, use:
 * <code>Ht7\Html\Translators\ImporterHt7::readFromArray($array)</code>. This
 * <code>ImporterHt7</code> class can also transform a DOMElement-tree into an
 * instance of the Tag class.
 *
 * @author Thomas Pluess
 */
class Tag extends Node implements \JsonSerializable, IteratorAggregate
{

    /**
     * @var     AttributeList
     */
    protected $attributes;

    /**
     * The content of the current HTML element.
     *
     * @var     NodeList    The content of the current tag, which can be a Text-
     *                      or a Tag-instance.
     */
    protected $content;

    /**
     * @var     string      The name of the current HTML element.
     */
    protected $tagName;

    /**
     * Create an instance of the Tag class.
     *
     * @param   string  $tagName            The name of the current tag.
     * @param   mixed   $content            The content of the current Tag instance.
     * @param   array   $attributes         Indexed array of Attribute instances.
     */
    public function __construct($tagName = 'div', $content = [], array $attributes = [])
    {
        $this->setTagName($tagName);
        $this->setContent($content);
        $this->setAttributes($attributes);
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

        if ($this->isSelfClosing()) {
            $html = '<' . $tagName . $attrStrSanitized . ' />';
        } else {
            $html = '<' . $tagName . $attrStrSanitized . '>';
            $html .= $this->getContent();
            $html .= '</' . $tagName . '>';
        }

        return $html;
    }

    /**
     * Get the defined attributes of the current tag instance.
     *
     * @return  AttributeList           The attributes of the present tag.
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get the content of the current HTML element.
     *
     * @return  NodeList                The content of the current HTML element.
     */
    public function getContent()
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
    public function getIterator()
    {
        return $this->getIteratorPreOrder();
    }

    /**
     * Get the tag name of the current element.
     *
     * @return  string          The tag name.
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Get a tree iterator which goes first every tree up before searching the
     * next.
     */
    public function getTreeIteratorHorizontal()
    {

    }

    /**
     * Get a tree iterator which searches first every sibling before going up to
     * the next level.
     */
    public function getIteratorPreOrder()
    {
        return new PreOrderIterator($this);
    }

    /**
     * Whetever the current tag is self closing or not.
     *
     * @return  boolean         True if the current element is self closing.
     */
    public function isSelfClosing()
    {
        return SelfClosing::is($this->getTagName());
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'attributes' => $this->getAttributes()->jsonSerialize(),
            'content' => $this->getContent()->jsonSerialize(),
            'tag' => $this->tagName
        ];
    }

    /**
     * Set the attributes of the current HTML element.
     *
     * @param   mixed   $attributes         Indexed array of
     *                                      <code>\Ht7\Html\Attribute</code>
     *                                      instances or an instance of
     *                                      <code>AttributeList</code>.
     */
    public function setAttributes($attributes)
    {
        if ($attributes instanceof AttributeList) {
            $this->attributes = $attributes;
        } elseif (is_array($attributes)) {
            $this->attributes = new AttributeList($attributes);
        } else {
            throw new InvalidDatatypeException(
                    'attributes',
                    $attributes,
                    ['array'],
                    [NodeList::class]
            );
        }
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
     * @param   mixed       $content        The content of the current Tag
     *                                      instance. This must be a NodeList
     *                                      instance or an array.
     * @throws  BadMethodCallException
     * @throws  InvalidArgumentException
     */
    public function setContent($content)
    {
        if (!empty($content) && $this->isSelfClosing()) {
            $msg = 'This tag (%s) can not have content, because it is self'
                    . ' closing.';
            $e = sprintf($msg, gettype($this->getTagName()));

            throw new BadMethodCallException($e);
        }

        $this->content = $content instanceof NodeList ? $content : new NodeList($content);
    }

    /**
     * Set the name of the current tag.
     *
     * @param   string      $name           The tag name of the current HTML element.
     * @throws  InvalidArgumentException
     */
    public function setTagName($name)
    {
        if (is_string($name)) {
            $this->tagName = $name;
        } else {
            throw new InvalidDatatypeException('tag name', $name, ['string']);
        }
    }

    // toArray()??
}
