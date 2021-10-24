<?php

namespace Ht7\Html\Utilities;

use \Ht7\Html\Tag;
use \Ht7\Html\Text;
use \Ht7\Html\Lists\AttributeList;
use \Ht7\Html\Utilities\AbstractImporter;

/**
 * Transform several core DOM objects into a ht7 tag tree.
 *
 * @author Thomas Plüss
 */
class ImporterDom extends AbstractImporter
{

    /**
     * Create a <code>Tag</code> instance from a core <code>DOMElement</code>
     * object.
     *
     * @param   \DOMElement $el
     * @return  Tag
     */
    public function createTag(\DOMElement $el)
    {
        $tag = new Tag($el->tagName);
        $tag->setAttributes($this->getAttributeList($el));

        return $tag;
    }

    /**
     * Create a <code>Text</code> instance from a core <code>DOMText</code> object.
     *
     * @param   \DOMText    $node
     * @return  Text
     */
    public function createText(\DOMText $node)
    {
        return new Text($node->nodeValue);
    }

    /**
     * Get an array of attributes according to the present \DOMElement.
     *
     * @param   \DOMElement  $el         The element to collect the attributes from.
     * @return  array                   Assoc array with the related attribute
     *                                  name as key and its attribute value as
     *                                  array value.
     */
    public function getAttributeArray(\DOMElement $el)
    {
        $attributes = [];

        if ($el->hasAttributes()) {
            foreach ($el->attributes as $attribute) {
                $attributes[$attribute->nodeName] = $attribute->nodeValue;
            }
        }

        return $attributes;
    }

    /**
     * Get an AttributeList instance with the attribute instance according to
     * the present \DOMElement.
     *
     * @param   \DOMElement      $el     The element to gain the attributes from.
     * @return  AttributeList           The list with the found attributes.
     */
    public function getAttributeList(\DOMElement $el)
    {
        $attributes = new AttributeList();

        if ($el->hasAttributes()) {
            foreach ($el->attributes as $attribute) {
                $attributes->addPlain($attribute->nodeName, $attribute->nodeValue);
            }
        }

        return $attributes;
    }

    /**
     * Build a tag tree from a \DOMElement.
     *
     * This is a recursive method. It recalls itself until there is no further
     * \DOMElement instance to parse.
     *
     * @param   \DOMElement  $el         The element to parse.
     * @param   Tag         $tag        The Tag instance to add the current
     *                                  element to.
     * @return  Tag                     The prepared Tag instance.
     * @throws \InvalidArgumentException
     */
    public function read($el, Tag $tag = null)
    {
        if (!($el instanceof \DOMElement)) {
            $e = 'Element must be an instance of \DOMElement, found '
                    . is_object($el) ? get_class($el) : gettype($el);

            throw new \InvalidArgumentException($e);
        }

        if (empty($tag)) {
            $tag = $this->createTag($el);
        }

        $elements = [];

        foreach ($el->childNodes as $node) {
            if ($node instanceof \DOMElement) {
                $elements[] = $this->read($node);
            } elseif ($node instanceof \DOMText) {
                $elements[] = $this->createText($node);
            }
        }

        $tag->setContent($elements);

        return $tag;
    }

}
