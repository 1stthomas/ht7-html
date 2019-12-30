<?php

namespace Ht7\Html\Translators;

use \DOMDocument;
use \DOMElement;
use \DOMText;
use \InvalidArgumentException;
use \Ht7\Base\Exceptions\InvalidDatatypeException;
use \Ht7\Html\Tag;
use \Ht7\Html\Text;
use \Ht7\Html\Lists\AttributeList;

/**
 * This class can transform several kinds of tag definitions into a ht7 tag tree.
 *
 * @author Thomas Plüss
 */
class ImporterHt7
{

    /**
     * Get an array of attributes according to the present DOMElement.
     *
     * @param   DOMElement  $el         The element to gain the attributes from.
     * @return  array                   Assoc array with the related attribute
     *                                  name as key and its attribute value as
     *                                  array value.
     */
    public static function getAttributeArrayFromDom(DOMElement $el)
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
     * the present DOMElement.
     *
     * @param   DOMElement      $el     The element to gain the attributes from.
     * @return  AttributeList           The list with the found attributes.
     */
    public static function getAttributeListFromDom(DOMElement $el)
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
     * Build a tag tree from an array.
     *
     * E.g.
     * <code>
     * $arr = [
     *     'tag' => 'div',
     *     'content' => ['simple text'],
     *     'attributes' => ['class' => 'btn', 'id' => 'btn-123']
     * ];
     * </code>
     * will produce <code>\<div>simple text<\/div></code>
     *
     * @param   array   $arr
     * @return  Tag
     * @throws  InvalidArgumentException
     */
    public static function readFromArray(array $arr)
    {
        if (empty($arr)) {
            return null;
        } elseif (isset($arr['tag'])) {
            $attributes = isset($arr['attributes']) ? $arr['attributes'] : [];
            $els = isset($arr['content']) ? $arr['content'] : [];
            $content = [];

            foreach ($els as $el) {
                $content[] = static::createContentElement($el);
            }

            return new Tag($arr['tag'], $content, $attributes);
        } else {
            $e = 'The definition "tag" is missing.';

            throw new InvalidArgumentException($e);
        }
    }

    /**
     * Build a tag tree from a DOMElement.
     *
     * This is a recursive method. It recalls itself until there is no further
     * DOMElement instance to parse.
     *
     * @param   DOMElement  $el         The element to parse.
     * @param   Tag         $tag        The Tag instance to add the current
     *                                  element to.
     * @return  Tag                     The prepared Tag instance.
     */
    public static function readFromDom(DOMElement $el, Tag $tag = null)
    {
        if (empty($tag)) {
            $tag = new Tag($el->tagName);
            $tag->setAttributes(static::getAttributeListFromDom($el));
        }

        $elements = [];

        foreach ($el->childNodes as $node) {
            if ($node instanceof DOMElement) {
                $elements[] = static::readFromDom($node);
            } elseif ($node instanceof DOMText) {
                $elements[] = new Text($node->nodeValue);
            }
        }

        $tag->setContent($elements);

        return $tag;
    }

    /**
     * Get a Tag representation of the html in the present string.
     *
     * This function uses internally the DOMDocument and its anchestors to gain
     * a valid HTML DOM. This Dom is parsed to gain the Tag tree.
     *
     * @param   string  $html               A string with a valid HTML structure.
     * @return  Tag                         The Tag tree.
     * @throws  InvalidArgumentException
     */
    public static function readFromHtmlString($html)
    {
        if (!is_string($html)) {
            throw new InvalidDatatypeException(
                    'The input string',
                    $html,
                    ['string']
            );
        } else {
            $html = trim($html);

            if (strpos($html, '<') !== 0) {
                throw new InvalidArgumentException();
            }
        }

        $els = [];
        $dom = new DOMDocument();
        $dom->loadHTML($html);

        if (static::hasTagInString($html, 'html')) {
            $els = $dom->getElementsByTagName('html');
        } else {
            $tag = static::getStartingTag($html);

            if (!empty($tag)) {
                $els = $dom->getElementsByTagName($tag);
            }
        }

        return count($els) > 0 ? static::readFromDom($els[0]) : null;
    }

    /**
     * Create a Node instance according to the datatype of the present element.
     *
     * @param   mixed   $el             The present element on which is decided
     *                                  what has to be done next.
     *                                  If it is a scalar value, a Text instance
     *                                  will be created. Otherwise a next
     *                                  recursive iteration will be released.
     * @return  mixed                   Text or Tag instance according to the
     *                                  datatype of the present element.
     */
    protected static function createContentElement($el)
    {
        if (is_scalar($el)) {
            return new Text($el);
        } else {
            return static::readFromArray($el);
        }
    }

    /**
     * Get the starting tag of the present HTML string.
     *
     * @param   string  $html           The HTML string to search the starting
     *                                  tag.
     * @return  string|null             A string of the found tag name, or null
     *                                  if no tag could be found.
     */
    protected static function getStartingTag($html)
    {
        $matches = [];

        preg_match('/^<\s*([a-zA-Z]+)[^>]*>/', $html, $matches);

        return count($matches) > 1 ? $matches[1] : null;
    }

    /**
     * Search for a specific tag in a HTML string.
     *
     * @param   string  $html           The haystack.
     * @param   string  $tag            The HTML tag to find.
     * @return  boolean                 True if the tag in question could be found.
     */
    protected static function hasTagInString($html, $tag)
    {
        return preg_match("/\s*?" . $tag . "\b[^>]*>/", $html) === 1 ? true : false;
    }

}
