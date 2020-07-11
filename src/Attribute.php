<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ht7\Html;

use \InvalidArgumentException;
use \Ht7\Base\Lists\HashListable;
use \Ht7\Html\Renderable;

/**
 * Description of Attribute
 *
 * @author 1stthomas
 */
class Attribute implements HashListable, \JsonSerializable, Renderable
{

    /**
     * @var     string      The attribute name.
     */
    protected $name;

    /**
     * @var     string      The attribute value.
     */
    protected $value;

    /**
     * Create an instance of the attribute class.
     *
     * @param   string  $name           The attribute name which must not be empty.
     * @param   mixed   $value          The attribute value.
     */
    public function __construct($name, $value)
    {
        $this->setName($name);
        $this->setValue($value);
    }

    /**
     * Get a string representation of the current class.
     *
     * @return  string                  The output will be as following:
     *                                  <code>attributeName="AttributeValue"</code>.
     */
    public function __toString()
    {
        $value = $this->getValue();

        return $this->getName() . ($value === '' ? '' : '="' . $value . '"');
    }

    /**
     * @Overridden
     */
    public function getHash()
    {
        return $this->getName();
    }

    /**
     * Get the name of the present attribtue.
     *
     * @return  string                  The attribute name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of the present attribute.
     *
     * @return  mixed                   The attribute value.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->getValue();
    }

    /**
     * Set the name of the current attribute instance.<br />
     * The name must be a string and must not be empty.
     *
     * @param   string  $name           The attribute name.
     * @throws  InvalidArgumentException
     */
    public function setName($name)
    {
        if (empty($name)) {
            $msg = 'The attribute name must not be empty.';
            $e = sprintf($msg, gettype($name));

            throw new InvalidArgumentException($e);
        } elseif (is_string($name)) {
            $this->name = $name;
        } else {
            $msg = 'The attribute name must be a string, found %s.';
            $e = sprintf($msg, gettype($name));

            throw new InvalidArgumentException($e);
        }
    }

    /**
     * Set the value of the current attribute instance.<br />
     * The value must be either string, int or float.
     *
     * @param   mixed   $value          The attribute value.
     * @throws  InvalidArgumentException
     */
    public function setValue($value)
    {
        if (is_string($value) || is_int($value) || is_float($value)) {
            $this->value = $value;
        } else {
            $msg = 'The attribute value must be a string, int or float, found %s.';
            $e = sprintf($msg, gettype($value));

            throw new InvalidArgumentException($e);
        }
    }

}
