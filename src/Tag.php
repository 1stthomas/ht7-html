<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ht7\Html;

/**
 * Description of Tag
 *
 * @author 1stthomas
 */
use \InvalidArgumentException;

class Tag
{

    /**
     * @var     array       An indexed array of Attribute instances.
     */
    protected $attributes = [];

    /**
     * The content of the current HTML element.
     *
     * @var     mixed       The content must be one of the following data types:
     *                      string, int, float or an indexed array of string or
     *                      Html-elements of this class.
     */
    protected $content;

    /**
     * @var     array       Indexed array of self closing HTML tags.
     * @see                 https://developer.mozilla.org/en-US/docs/Glossary/Empty_element
     */
    protected static $selfClosingTags = [
        'area',
        'base',
        'br',
        'col',
        'embed',
        'hr',
        'img',
        'input',
        'keygen',
        'link',
        'meta',
        'param',
        'source',
        'track',
        'wbr'
    ];

    /**
     * @var     string      The name of the current HTML element.
     */
    protected $tagName;

    /**
     * Create an instance of the Tag class.
     *
     * @param   string  $tagName            The name of the current tag.
     * @param   mixed   $content            One of the following datatypes:
     *                                      string, int, float or an indexed
     *                                      array of Tag instances.
     * @param   array   $attributes         Indexed array of Attribute instances.
     */
    public function __construct($tagName = 'div', $content = '', array $attributes = [])
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
        $attrStr = $this->getAttributes();
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
     * Add an attribute instance to the attribute array of the current tag instance.
     *
     * @param   Attribute   $attribute      The attribute to be added.
     * @return  Tag
     */
    public function addAttribute(Attribute $attribute)
    {
        $this->attributes[$attribute->getName()] = $attribute;

        return $this;
    }

    /**
     * Add an attribute to the current attribute instance.
     *
     * @param   string  $name           The attribute name.
     * @param   mixed   $value          The attribute value as string, int or float
     * @return  Tag
     */
    public function addAttributePlain($name, $value)
    {
        $this->attributes[$name] = new Attribute($name, $value);

        return $this;
    }

    /**
     * Get the defined attributes of the current tag instance.
     *
     * @param   string  $outputType     For "str" or "string" the returned
     *                                  attributes will be a related string.
     *                                  Otherwise a string representation of the
     *                                  defined attributes will be returned.
     * @return  mixed                   String or indexed array.
     */
    public function getAttributes($outputType = 'str')
    {
        ksort($this->attributes);
        $attributes = $this->attributes;

        if ($outputType === 'str' || $outputType === 'string') {
            $attributes = implode(' ', $this->attributes);
        }

        return $attributes;
    }

    /**
     * Get the content of the current HTML element.
     *
     * @param   string      $outputType     'str' if possible Html-instances should
     *                                      be transformed into a string. Otherwise
     *                                      no trransformation will be performed.
     * @return  mixed                       The content of the current HTML element.
     */
    public function getContent($outputType = 'str')
    {
        $content = $this->content;

        if ($outputType === 'str') {
            if (is_array($this->content)) {
                $content = '';

                foreach ($this->content as $item) {
                    $content .= '' . $item;
                }
            }
        }

        return $content;
    }

    public function getIterator()
    {
        // @todo.....
        // see https://www.php.net/manual/en/class.iterator.php
        // kann dann mittels array_search(array_keys($content od $attr), $term)
        // ein element finden oder nur mi array_keys die jeweilige position abspeichern.
    }

    /**
     * Get a list of all self closing elements.
     *
     * @return  array           Indexed array of all self closing tag names.
     */
    public static function getSelfClosingTags()
    {
        return self::$selfClosingTags;
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
     * Whetever the current tag is self closing or not.
     *
     * @return  boolean         True if the current element is self closing.
     */
    public function isSelfClosing()
    {
        return in_array($this->getTagName(), self::getSelfClosingTags());
    }

    /**
     * Set the attributes of the current HTML element.
     *
     * @param   array   $attributes         Indexed array of
     *                                      <code>\Ht7\Html\Attribute</code>
     *                                      instances.
     */
    public function setAttributes(array $attributes)
    {
        if (empty($attributes)) {
            $this->attributes = [];
        } elseif (gettype(array_keys($attributes)[0]) === 'integer') {
            foreach ($attributes as $attr) {
                if ($attr instanceof Attribute) {
                    $this->addAttribute($attr);
                } else {
                    throw new BadMethodCallException('Unsupported');
                }
            }
        } else {
            $this->setAttributesPlain($attributes);
        }
    }

    /**
     * Set the attributes of the current HTML element.
     *
     * @param   array   $attributes         Assoc array with the attribute name
     *                                      as key and the attribute value as value.
     */
    public function setAttributesPlain(array $attributes)
    {
        // Empty the existing ones.
        $this->attributes = [];

        foreach ($attributes as $name => $value) {
            $this->addAttributePlain($name, $value);
        }
    }

    /**
     * Set the inner content of the current tag.
     *
     * The content will not be considered if the current tag is self closing.
     *
     * @param   mixed       $content        Allowed data types: string, int, float,
     *                                      array. If the content is an array, its
     *                                      items must be a string or an instance
     *                                      of this class.
     * @throws  InvalidArgumentException
     */
    public function setContent($content)
    {
        if (is_string($content) || is_int($content) || is_float($content)) {
            $this->content = '' . $content;
        } elseif (is_array($content)) {
            $this->content = $content;
        } else {
            $msg = 'The content must be a string, int, float or array, found %s.';
            $e = sprintf($msg, gettype($content));

            throw new InvalidArgumentException($e);
        }
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
            $msg = 'The tag name must be a string, found %s.';
            $e = sprintf($msg, gettype($name));

            throw new InvalidArgumentException($e);
        }
    }

}
