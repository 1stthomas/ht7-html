<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ht7\Html;

use \InvalidArgumentException;
use \Ht7\Base\Exceptions\InvalidDatatypeException;

/**
 * Description of Attribute
 *
 * @author 1stthomas
 */
class Text extends Node
{

    /**
     * Create an instance of the text element.
     *
     * @param   string  $text           The content.
     */
    public function __construct($text)
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
    public function getContent()
    {
        return parent::getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->getContent();
    }

    /**
     * Set the content.
     *
     * Allowed datatypes:
     * - string
     * - integer
     * - float
     *
     * @param   string  $text           The content.
     * @throws  InvalidArgumentException
     */
    public function setContent($text)
    {
        if (is_scalar($text)) {
            $this->content = (string) $text;
        } else {
            throw new InvalidDatatypeException('the text content', $text, ['scalar']);
        }
    }

}
