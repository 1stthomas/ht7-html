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
 * @todo    Diese Klasse sollte eigentlich Importer oder wenigstens Ht7Importer/ImporterHt7 heissen!
 *          Dann kann auch eine weitere Klasse erstellt werden, die Exporter oder so
 *          heisst.
 *
 * @author Thomas Plüss
 */
class ImporterHt7
{

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

    protected static function createContentElement($el)
    {
        if (is_scalar($el)) {
            return new Text($el);
        } else {
            return static::readFromArray($el);
        }
    }

    protected static function getStartingTag($html)
    {
        $matches = [];

        preg_match('/^<\s*([a-zA-Z]+)[^>]*>/', $html, $matches);

        return count($matches) > 1 ? $matches[1] : null;
    }

    protected static function hasTagInString($html, $tag)
    {
        return preg_match("/\s*?" . $tag . "\b[^>]*>/", $html) === 1 ? true : false;
    }

}
