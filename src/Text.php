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
     * @var     string      The attribute name.
     */
    protected $text = '';

    /**
     * Create an instance of the text element.
     *
     * @param   string  $text           The content.
     */
    public function __construct($text)
    {
        $this->setText($text);
    }

    /**
     * Get a string representation of the current class.
     *
     * @return  string                  The content.
     */
    public function __toString()
    {
        return $this->getText();
    }

    /**
     * Get the content.
     *
     * @return  string                  The content of this element.
     */
    public function getText()
    {
        return $this->text;
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
    public function setText($text)
    {
        if (is_scalar($text)) {
            $this->text = (string) $text;
        } else {
            throw new InvalidDatatypeException('the text content', $text, ['scalar']);
        }
    }

}
